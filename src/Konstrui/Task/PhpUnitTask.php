<?php

namespace Konstrui\Task;

/**
 * Performs PHPUnit tests. Supports:
 * - custom configuration path
 * - custom phpunit executable path.
 *
 * It will try to resolve phpunit path automatically if not provided. Precedence
 * will have phpunit located inside vendor/bin/phpunit (over phpunit) because
 * it is likely to use custom library version if provided as a composer
 * dependency.
 */
class PhpUnitTask extends ExecutableTask
{
    /**
     * @var string|null
     */
    protected $configurationPath;

    /**
     * @var string|null
     */
    protected $phpUnitPath;

    /**
     * @param string|null $configurationPath
     * @param string|null $phpUnitPath
     */
    public function __construct(
        $configurationPath = null,
        $phpUnitPath = null
    ) {
        $this->configurationPath = $configurationPath;
        $this->phpUnitPath = $phpUnitPath;
    }

    /**
     * {@inheritdoc}
     */
    public function perform()
    {
        $this->command = sprintf(
            '%s%s',
            $this->resolvePhpUnitPath($this->phpUnitPath),
            $this->resolveConfigurationFlag()
        );

        parent::perform();
    }

    /**
     * Resolves path to phpunit executable.
     *
     * Preferably needs to be resolved during runtime (hence overriding of perform method,
     * not the constructor). This is due to the fact that we might want to run composer
     * first (as a task) and then PHPUnit specified as dependency. In this case
     * vendor/bin/phpunit path does not exist before composer installation finishes,
     * so at the moment of this object creation it would be resolved to global phpunit
     * instead of the vendor one.
     *
     * @param string|null $phpUnitPath
     *
     * @return string
     */
    protected function resolvePhpUnitPath($phpUnitPath)
    {
        if (empty($phpUnitPath)) {
            if ($this->isVendorPhpUnitPresent()) {
                return 'vendor/bin/phpunit';
            }

            return 'phpunit';
        }

        return $phpUnitPath;
    }

    /**
     * @return bool
     */
    protected function isVendorPhpUnitPresent()
    {
        return file_exists('vendor/bin/phpunit');
    }

    /**
     * @return string
     */
    protected function resolveConfigurationFlag()
    {
        if (!empty($this->configurationPath)) {
            return sprintf(' --configuration=%s', $this->configurationPath);
        }

        return '';
    }
}
