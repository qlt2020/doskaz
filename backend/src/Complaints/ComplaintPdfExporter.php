<?php


namespace App\Complaints;

use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\OfficeRequest;

class ComplaintPdfExporter implements ComplaintExporter
{
    private ComplaintDocExporter $complaintDocExporter;

    private Client $client;

    public function __construct(ComplaintDocExporter $complaintDocExporter)
    {
        $this->complaintDocExporter = $complaintDocExporter;
        $this->client = new Client('http://localhost:3003');
    }

    public function execute(int $id, string $locale): \SplFileObject
    {
        $document = $this->complaintDocExporter->execute($id, $locale);
        $doc = DocumentFactory::makeFromPath('complaint.docx', $document->getPathname());
        $request = new OfficeRequest([$doc]);
        $path = tempnam('/tmp', 'pdf');
        $this->client->store($request, $path);
        unlink($document->getPathname());
        return new \SplFileObject($path);
    }
}
