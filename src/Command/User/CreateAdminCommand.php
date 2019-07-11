<?php declare(strict_types = 1);

namespace App\Command\User;

use App\Model\CreateAdministrator\CreateAdministratorFacade;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateAdminCommand extends ContainerAwareCommand
{
    /** @var CreateAdministratorFacade */
    private $administratorFacade;

    public function __construct(
        CreateAdministratorFacade $administratorFacade,
        ?string $name = null
    ) {
        $this->administratorFacade = $administratorFacade;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setName('app:admin:create')
            ->setDescription('Create admin with some credentials and roles.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->administratorFacade->createUser($io);

        $io->success('Admin created.');
    }
}
