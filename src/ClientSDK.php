<?php
/**
 * Created by PhpStorm.
 * This file is part of the keystack-php-client-sdk project.
 * Filename: ManagerSDK.php
 * Namespace: KeyStackApp
 * User: szilard
 * Date: 03.11.2025
 * Time: 20:52
 */

namespace KeyStackApp;

use KeyStack\Manager\Model\AddManifestRecord200Response;
use KeyStack\Manager\Model\ManifestAddSchema;
use KeyStackApp\Adapter\TokenStorageAdapterInterface;
use KeyStackApp\Authentication\Model\ActivationDelete;
use KeyStackApp\Authentication\Model\ActivationList;
use KeyStackApp\Authentication\Model\LicenseCreateInput;
use KeyStackApp\Authentication\Model\LicenseDelete;
use KeyStackApp\Authentication\Model\LicenseList;
use KeyStackApp\Authentication\Model\LicenseRecord;
use KeyStackApp\Authentication\Model\LicenseUpdateInput;
use KeyStackApp\Service\ConfiguredApi;

class ClientSDK extends ConfiguredApi
{
    public function __construct(
        ?TokenStorageAdapterInterface $adapter = null,
        ?string $apiKey = null
    ) {
        parent::__construct($adapter, $apiKey);
    }

    /**
     * @throws \Exception
     */
    public function addManifest(ManifestAddSchema $manifest): AddManifestRecord200Response
    {
        return $this->handleApiError(
            fn() => $this->api->addManifestRecord($manifest)
        );
    }

    /**
     * @throws \Exception
     */
    public function createLicense(LicenseCreateInput $createInput): LicenseRecord
    {
        return $this->handleApiError(
            fn() => $this->api->createLicense($createInput)
        );
    }

    /**
     * @throws \Exception
     */
    public function deleteActivation(string $internalLicenseId, string $activationId): ActivationDelete
    {
        return $this->handleApiError(
            fn() => $this->api->deleteActivation($internalLicenseId, $activationId)
        );
    }

    /**
     * @throws \Exception
     */
    public function deleteLicense(string $internalLicenseId): LicenseDelete
    {
        return $this->handleApiError(
            fn() => $this->api->deleteLicense($internalLicenseId)
        );
    }

    /**
     * @throws \Exception
     */
    public function deleteManifestRecord(string $key): void
    {
        $this->handleApiError(
            fn() => $this->api->deleteManifestRecord($key)
        );
    }

    /**
     * @throws \Exception
     */
    public function getActivations(string $internalLicenseId): ActivationList
    {
        return $this->handleApiError(
            fn() => $this->api->getActivations($internalLicenseId)
        );
    }

    /**
     * @throws \Exception
     */
    public function getAllLicenses(): LicenseList
    {
        return $this->handleApiError(
            fn() => $this->api->getAllLicenses()
        );
    }

    /**
     * @throws \Exception
     */
    public function getLicense(string $internalLicenseId): LicenseRecord
    {
        return $this->handleApiError(
            fn() => $this->api->getLicense($internalLicenseId)
        );
    }

    /**
     * @throws \Exception
     */
    public function updateLicense(string $internalLicenseId, LicenseUpdateInput $input): LicenseRecord
    {
        return $this->handleApiError(
            fn() => $this->api->updateLicense($internalLicenseId, $input)
        );
    }
}