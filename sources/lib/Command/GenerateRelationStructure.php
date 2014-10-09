<?php
/*
 * This file is part of Pomm's Cli package.
 *
 * (c) 2014 Grégoire HUBERT <hubert.greg@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PommProject\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use PommProject\Foundation\Inflector;
use PommProject\Cli\Command\BaseGenerate;
use PommProject\Cli\Generator\StructureGenerator;

/**
 * GenerateRelationStructure
 *
 * Command to scan a relation and (re)build the according structure file.
 *
 * @package Cli
 * @copyright 2014 Grégoire HUBERT
 * @author Grégoire HUBERT
 * @license X11 {@link http://opensource.org/licenses/mit-license.php}
 * @see Command
 */
class GenerateRelationStructure extends BaseGenerate
{
    /**
     * configure
     *
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('generate:structure')
            ->setDescription('Generate a RowStructure file based on table schema.')
            ->setHelp(<<<HELP
HELP
        )
            ;
        parent::configure();
    }

    /**
     * execute
     *
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->filename = trim(
            sprintf(
                "%s/%s/%s/%s/AutoStructure/%s.php",
                ltrim($this->prefix_dir, '/'),
                str_replace('\\', '/', trim($this->prefix_ns, '\\')),
                Inflector::studlyCaps($input->getArgument('config-name')),
                Inflector::studlyCaps(sprintf("%s_schema", $this->schema)),
                Inflector::studlyCaps($this->relation)
            ),
            '/'
        );

        $this->namespace = sprintf(
            "%s\\%s\\%s\\AutoStructure",
            $this->prefix_ns,
            Inflector::studlyCaps($input->getArgument('config-name')),
            Inflector::studlyCaps(sprintf("%s_schema", $this->schema))
        );

        (new StructureGenerator(
            $this->getSession(),
            $this->schema,
            $this->relation,
            $this->filename,
            $this->namespace
        ))->generate($input, $output);
    }
}
