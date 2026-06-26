<?php

namespace App\Service;

use App\Entity\Payment;
use Dompdf\Dompdf;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class QuittanceGenerator
{
    public function __construct(
        private Environment $twig,
        #[Autowire('%receipts_directory%')] private string $receiptsDirectory,
    ) {
    }

    /**
     * Renders and stores the PDF receipt for the given payment, unless it was already generated.
     * Returns the absolute filesystem path of the PDF.
     */
    public function generate(Payment $payment): string
    {
        if ($payment->getReceiptPath() !== null && is_file($payment->getReceiptPath())) {
            return $payment->getReceiptPath();
        }

        $html = $this->twig->render('pdf/quittance.html.twig', [
            'payment' => $payment,
            'lease' => $payment->getLease(),
            'property' => $payment->getLease()->getProperty(),
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        $filesystem = new Filesystem();
        $filesystem->mkdir($this->receiptsDirectory);

        $filename = sprintf(
            'quittance-bail-%d-%s.pdf',
            $payment->getLease()->getId(),
            $payment->getPeriod()->format('Y-m')
        );
        $path = $this->receiptsDirectory . '/' . $filename;
        $filesystem->dumpFile($path, $dompdf->output());

        $payment->setReceiptPath($path);
        $payment->setReceiptGeneratedAt(new \DateTimeImmutable());

        return $path;
    }
}
