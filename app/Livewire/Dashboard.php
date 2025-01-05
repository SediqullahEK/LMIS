<?php

namespace App\Livewire;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Livewire\Component;

class Dashboard extends Component
{
    public $text = ''; // Input for QR code
    public $qrCodeDataUrl = ''; // Base64 image URL of the QR code

    public function generateQrCode()
    {
        $logoPath = public_path('storage/system_images/logo.webp');
        if (!empty($this->text)) {
            $builder = new Builder(
                writer: new PngWriter(),
                writerOptions: [],
                validateResult: false,
                data: $this->text,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                logoPath: $logoPath, // Add a logo path if needed
                logoResizeToWidth: 60, // Resize logo to 50px width
                logoPunchoutBackground: true,
                labelText: '',
                labelFont: new OpenSans(20),
                labelAlignment: LabelAlignment::Center
            );

            $result = $builder->build();
            $this->qrCodeDataUrl = 'data:image/png;base64,' . base64_encode($result->getString());
        } else {
            $this->qrCodeDataUrl = '';
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
