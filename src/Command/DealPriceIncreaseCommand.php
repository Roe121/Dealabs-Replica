<?php

namespace App\Command;

use App\Entity\Deal;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'deal:price:increase',
    description: 'Cette commande permet d\'augmenter le prix des deals',
)]
class DealPriceIncreaseCommand extends Command
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('price', InputArgument::REQUIRED, 'Montant de l\'augmentation du prix')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'ID du deal à mettre à jour')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Mettre à jour tous les deals');;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        if ($input->getOption('all') && $input->getOption('id')) {
            throw new \Exception('Vous ne pouvez pas utiliser les arguments "all" et "id" en même temps');
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $io = new SymfonyStyle($input, $output);
        $increaseAmount =  $input->getArgument('price');


        if ($input->getOption('all')) {
            $deals = $this->entityManager->getRepository(Deal::class)->findAll();

            if (empty($deals)) {
                $io->warning('Aucun deal trouvé.');
                return Command::FAILURE;
            }

            $tableData = [];
            $progressBar = new ProgressBar($output, count($deals));
            $progressBar->start();
            foreach ($deals as $deal) {
                $oldPrice = $deal->getPrice();
                $newPrice = $oldPrice + $increaseAmount;
                $deal->setPrice($newPrice);
                $tableData[] = [$deal->getId(), $oldPrice, $newPrice];
                $progressBar->advance();
            }

            $progressBar->finish();
            $io->newLine();

            $io->table(['ID', 'Ancien Prix', 'Nouveau Prix'], $tableData);
            $io->success('Tous les deals ont été mis à jour.');
        } else {
            $dealId = $input->getOption('id');
            $deal = $this->entityManager->getRepository(Deal::class)->find($dealId);

            if (!$deal) {
                $io->error("Aucun deal trouvé avec l'ID $dealId.");
                return Command::FAILURE;
            }

            $oldPrice = $deal->getPrice();
            $deal->setPrice($deal->getPrice() + $increaseAmount);


            $io->success("Deal #$dealId : Ancien prix = $oldPrice €, Nouveau prix = " . $deal->getPrice() . " €");
        }

        $this->entityManager->flush();


        return Command::SUCCESS;
    }
}
