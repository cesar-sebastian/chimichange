<?php


namespace App\Command;

use App\Entity\Rate;
use App\Service\FixerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadRateCommand extends Command
{
    protected static $defaultName = 'rate:load';
    private $fixerService;
    private $entityManager;

    /**
     * LoadRateCommand constructor.
     */
    public function __construct(FixerService $fixerService, EntityManagerInterface $entityManager)
    {
        $this->fixerService = $fixerService;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Load table rate.')
            ->setHelp('This command allows load table rates from fixer.io')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Init Load',
            '============',
            '',
        ]);

        $data = $this->fixerService->getRates();

        foreach($data['rates'] as $key => $value)
        {
            $rate = new Rate();
            $rate->setCurrency($key);
            $rate->setValue($value);
            $this->entityManager->persist($rate);
        }

        $this->entityManager->flush();

        $output->write('Finished.');

        return Command::SUCCESS;

    }

}
