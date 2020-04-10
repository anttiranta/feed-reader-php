<?php
namespace App\Antti\FeedsAppItemImportBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;
use App\Antti\FeedsAppItemImportBundle\Logger\ConsoleLogger;
use App\Antti\FeedsAppItemImportBundle\Logger\LoggerComposite;
use App\Antti\FeedsAppItemImportBundle\Logger\Logger as DefaultLogger;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Transport;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Processor;

/**
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 * $ php bin/console feedsapp:item-import
 *
 * To output detailed information, increase the command verbosity:
 *
 * $ php bin/console feedsapp:item-import -vv
 */
class ImportItemCommand extends Command
{
    protected static $defaultName = 'feedsapp:item-import';

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var EntityManagerInterface 
     */
    private $entityManager;
    
    /**
     * @var LoggerInterface 
     */
    private $logger;
    
    /**
     * @var Transport 
     */
    private $transportJob;
    
    /**
     * @var Processor 
     */
    private $processorJob;
    
    /**
     * @var array 
     */
    private $urls = [];
    
    /**
     * @var UrlArgumentNormalizer 
     */
    private $urlArgumentNormalizer;

    public function __construct(
        EntityManagerInterface $em,
        UrlArgumentNormalizer $urlArgumentNormalizer,
        Transport $transportJob,
        Processor $processorJob    
    ){
        parent::__construct();
        
        $this->entityManager = $em;
        $this->urlArgumentNormalizer = $urlArgumentNormalizer;
        $this->transportJob = $transportJob;
        $this->processorJob = $processorJob;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription('Performs an import for specific items by specified URL(s)')
            ->addArgument('url', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'Item XML URL(s)');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
        
        $this->urls = $this->urlArgumentNormalizer->normalizeUrlArgument(
            $input->getArgument('url')
        );
        
        // Have to be done get ConsoleLogger to write lines to console
        $output->setVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
        
        $this->logger = new LoggerComposite([
            new ConsoleLogger($output),
            new DefaultLogger()
        ]);
        $this->transportJob->setLogger($this->logger);
        $this->processorJob->setLogger($this->logger);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Starting import');
        
        $items = [];
        foreach ($this->urls as $url) {
            $items = array_merge($items, $this->transportJob->process($url));
        }
        
        $result = $this->processorJob->processAll($items);
        
        $this->logger->info(
            sprintf('Import %s', $result ? ' was successful' : 'failed')
        );
        
        return $result ? 0 : 1;
    }
}
