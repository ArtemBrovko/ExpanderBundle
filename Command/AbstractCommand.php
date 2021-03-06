<?php

namespace Sli\ExpanderBundle\Command;

use Sli\ExpanderBundle\Misc\KernelProxy;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Sergei Lissovski <sergei.lissovski@gmail.com>
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    // marked as public to simplify unit testing
    public $kernelProxy;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kp = $this->kernelProxy;
        if (!$this->kernelProxy) {
            $this->kernelProxy = $kp = new KernelProxy('dev', true);
        }

        $kp->boot();
        $kp->cleanUp();

        try {
            $this->doExecute($kp, $input, $output);
        } catch (\Exception $e) {
            $kp->cleanUp();

            throw $e;
        }
    }

    /**
     * @param KernelProxy $kernelProxyProxy
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    abstract protected function doExecute(KernelProxy $kernelProxyProxy, InputInterface $input, OutputInterface $output);

    static public function clazz()
    {
        return get_called_class();
    }
} 