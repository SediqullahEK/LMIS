<?php

use App\Models\ActivityLog;
use donatj\UserAgent\UserAgentParser;
use GeoIp2\Database\Reader;

/**
 * Log user activity in the database.
 *
 * @param string $action The action performed (e.g., "Updated Record").
 * @param string|null $model The model class (e.g., "App\Models\User").
 * @param int|null $modelId The ID of the affected model.
 * @param array|null $changes Changes made to the model.
 */
function logActivity($action, $model = null, $modelId = null, $changes = null)
{
    $userAgent = request()->header('User-Agent');
    $ipAddress = request()->ip();

    // Parse User-Agent
    $uaParser = new UserAgentParser();
    $parsedUA = $uaParser->parse($userAgent);

    $browser = $parsedUA->browser(); // Correct method for browser
    $os = $parsedUA->platform(); // Correct method for platform (OS)
    $deviceType = determineDeviceType($userAgent); // Device Type

    // Get GeoIP information
    $geoData = getGeoData($ipAddress); // GeoIP data array

    // Log activity
    ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => $action,
        'model' => $model,
        'model_id' => $modelId,
        'changes' => $changes ? json_encode($changes) : null,
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'os' => $os,
        'browser' => $browser,
        'device_type' => $deviceType,
        'country' => $geoData['country'] ?? null,
        'city' => $geoData['city'] ?? null,
        'description' => generateDescription($action, $model, $modelId),
    ]);
}

/**
 * Determine the type of device based on the User-Agent.
 *
 * @param string $userAgent
 * @return string
 */
function determineDeviceType($userAgent)
{
    if (preg_match('/mobile/i', $userAgent)) {
        return 'Mobile';
    } elseif (preg_match('/tablet/i', $userAgent)) {
        return 'Tablet';
    }
    return 'Desktop';
}

/**
 * Get GeoIP information (country and city) based on IP address.
 *
 * @param string $ipAddress
 * @return array
 */
function getGeoData($ipAddress)
{
    try {
        $reader = new Reader(storage_path('geoip/GeoLite2-City.mmdb')); // Adjust the path
        $record = $reader->city($ipAddress);

        return [
            'country' => $record->country->name,
            'city' => $record->city->name,
        ];
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Generate a description for the activity log.
 *
 * @param string $action
 * @param string|null $model
 * @param int|null $modelId
 * @return string
 */
function generateDescription($action, $model, $modelId)
{
    $description = ucfirst($action);
    if ($model) {
        $description .= " on " . class_basename($model);
        if ($modelId) {
            $description .= " with ID: {$modelId}";
        }
    }
    return $description;
}
