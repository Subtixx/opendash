<?php

namespace App\Widgets\Cpu;

use App\Widgets\StatWidget;
use Override;
use RuntimeException;

class CpuTemperatureWidget extends StatWidget
{
    private const OS_FAMILY_WINDOWS = 'WIN';
    private const OS_FAMILY_LINUX = 'LINUX';
    private string $osFamily;
    private string $temperature;
    #[Override] public function process(): bool
    {
        if (stripos(PHP_OS_FAMILY, self::OS_FAMILY_WINDOWS) === 0) {
            $this->osFamily = self::OS_FAMILY_WINDOWS;
        } elseif (stripos(PHP_OS_FAMILY, self::OS_FAMILY_LINUX) === 0) {
            $this->osFamily = self::OS_FAMILY_LINUX;
        } else {
            throw new RuntimeException('Unsupported OS');
        }
        $this->temperature = $this->getCPUTemperatureString();
        return parent::process();
    }

    private function getCPUTemperatureString(): string
    {
        $temperature = $this->getCPUTemperature();
        // Unit: millidegree Celsius
        $temperature /= 1000;
        return sprintf('%.1f', $temperature);
    }

    private function getCPUTemperature(): int
    {
        $temperature = 0;
        if ($this->osFamily === self::OS_FAMILY_WINDOWS) {
            $str = shell_exec('wmic /namespace:\\\\root\wmi PATH MSAcpi_ThermalZoneTemperature get CurrentTemperature');
            if (preg_match('/CurrentTemperature\s+(\d+)/', $str, $matches)) {
                $temperature = (int)$matches[1];
            }
        } elseif ($this->osFamily === self::OS_FAMILY_LINUX) {
            if (is_readable('/sys/class/thermal/thermal_zone0/temp')) {
                $str = file_get_contents('/sys/class/thermal/thermal_zone0/temp');
                $temperature = (int)$str;
            }
        }

        return $temperature;
    }

    #[Override] protected function getTitle(): string
    {
        return "CPU Temperature";
    }

    #[Override] protected function getIcon(): string
    {
        return "heroicon-m-beaker";
    }

    #[Override] protected function getValue(): string
    {
        return $this->temperature . ' °C';
    }

    #[Override] protected function getColor(): int
    {
        $temperature = (float)$this->temperature;
        if ($temperature < 50) {
            return self::COLOR_SUCCESS;
        }

        if ($temperature < 70) {
            return self::COLOR_WARNING;
        }

        return self::COLOR_DANGER;
    }
}
