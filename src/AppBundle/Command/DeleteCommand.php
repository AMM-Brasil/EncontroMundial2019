<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class DeleteCommand extends Command
{
    protected static $defaultName = 'app:delete-old';

    private $etm;
    private $mailer;

    public function __construct(? string $name = null, EntityManagerInterface $etm, \Swift_Mailer $mailer)
    {
        parent::__construct($name);
        $this->etm = $etm;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Deleta inscrições antigas.')
            ->setHelp('Este comando deleta inscrições antigas.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inscricoes = $this->etm->getRepository('AppBundle:Inscricao')->findByAtrasados();
        foreach ($inscricoes as $inscricao) {
            $message = (new \Swift_Message('Inscrição cancelada'))
                ->setFrom(['comunicacao@amm-brasil.org' => 'Comunicação AMM'])
                ->setTo($inscricao->getEmail())
                ->setBody(
                    sprintf(
                        'O prazo de 48h para o pagamento da inscrição da sua Regional expirou, portanto a inscrição foi cancelada abrindo vaga para novos interessados. '.
                            'Caso ainda queira participar, acesse o sistema novamente e realize uma nova inscrição.<br>'.
                            '<b>Data da inscrição:</b> %s<br>'.
                            '<b>Expirou em:</b> %s',
                        $inscricao->getDataCriacao()->format('d/m/Y H:i:s'),
                        $inscricao->getDataLimitePagamento()->format('d/m/Y H:i:s')
                    ),
                    'text/html'
                );
            $this->mailer->send($message);
            $this->etm->remove($inscricao);
        }
        $this->etm->flush();
    }
}
