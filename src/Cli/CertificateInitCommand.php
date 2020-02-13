<?php

declare(strict_types=1);

/*
 * This file is part of the BetterTransbank\SDK project.
 * (c) Matías Navarro-Carter <mnavarrocarter@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BetterTransbank\SDK\Cli;

use OOPHP\OpenSSL\ConfigSpec;
use OOPHP\OpenSSL\CSR\DistinguishedName;
use OOPHP\OpenSSL\Pair\PrivateKey;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class GenerateCertificateCommand.
 */
class CertificateInitCommand extends Command
{
    protected static $defaultName = 'cert:init';

    protected function configure(): void
    {
        $this
            ->setDescription('Generates certificates for using Transbank products')
            ->setHelp('This command allows you to generate certificates automatically for using with Transbank services')
            ->addArgument('commerce-code', InputArgument::REQUIRED, 'The commerce code assigned by Transbank')
            ->addOption('output-path', '-o', InputOption::VALUE_OPTIONAL, 'The output path of the keys', getcwd());
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $commerceCode = $input->getArgument('commerce-code');
        if (!is_string($commerceCode)) {
            $io->error('Commerce code must be a string');

            return -1;
        }

        $path = $input->getOption('output-path');
        if (!is_string($path)) {
            $io->error('Output path must be a string');

            return -1;
        }
        $path = $this->ensurePathExists(rtrim($path, DIRECTORY_SEPARATOR));
        $keyBaseName = $path.DIRECTORY_SEPARATOR.$commerceCode;

        $privateKeyName = $keyBaseName.'.key';
        $csrName = $keyBaseName.'.csr';
        $certName = $keyBaseName.'.crt';

        $spec = ConfigSpec::default()
            ->withDuplicatedBits(); // This is for a stronger, but slower, key.

        // First, we generate the private key
        $privateKey = PrivateKey::generate($spec);

        // Then, we generate a certificate signing request
        $dn = DistinguishedName::blank()
            ->withCountry($io->ask('Country Code', 'CL'))
            ->withLocality($io->ask('City', 'Santiago'))
            ->withStateOrProvince($io->ask('Region', 'RM'))
            ->withCommonName($commerceCode) // Transbank requires the commerce code to be the common name
            ->withOrganization($io->ask('Commerce Name', ''))
            ->withEmailAddress($io->ask('Commerce Email', ''));

        $csr = $privateKey->createCSR($dn);

        // Then, we create a self signed certificate with that CSR, valid for six years.
        $cert = $csr->sign($privateKey, null, 365 * ((int) $io->ask('Years of certificate duration', '6')));

        $io->writeln(sprintf('Writing the keys to %s', $path));

        $privateKey->writeTo($privateKeyName);
        $csr->writeTo($csrName);
        $cert->writeTo($certName);

        $io->success('All your keys have been created and stored!');
        $io->note(
            "Now you have to send the $certName file to Transbank.".PHP_EOL.
            'Please take a look at https://better-transbank.mnavarro.dev/certification for more information.'
        );

        return 0;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function ensurePathExists(string $path): string
    {
        if (!is_dir($path) && !mkdir($path, 0755, true) && !is_dir($path)) {
            throw new \RuntimeException('Path does not exist or could not be created');
        }

        return $path;
    }
}
