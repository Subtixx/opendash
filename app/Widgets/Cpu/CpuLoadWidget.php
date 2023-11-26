<?php

namespace App\Widgets\Cpu;

use App\Widgets\StatWidget;
use Override;
use RuntimeException;

class CpuLoadWidget extends StatWidget
{
    private const OS_FAMILY_WINDOWS = 'WIN';
    private const OS_FAMILY_LINUX = 'LINUX';
    private string $osFamily;
    private string $loadPercent;

    #[Override] public function process(): bool
    {
        if (stripos(PHP_OS_FAMILY, self::OS_FAMILY_WINDOWS) === 0) {
            $this->osFamily = self::OS_FAMILY_WINDOWS;
        } elseif (stripos(PHP_OS_FAMILY, self::OS_FAMILY_LINUX) === 0) {
            $this->osFamily = self::OS_FAMILY_LINUX;
        } else {
            throw new RuntimeException('Unsupported OS');
        }
        $this->loadPercent = $this->getCPULoadString();
        return parent::process();
    }

    private function getCPULoadString(): string
    {
        $loadPercent = $this->getCPULoad();
        return sprintf('%.1f', $loadPercent);
    }

    private function getCPULoad(): int
    {
        $cpuUsage = 0;
        if ($this->osFamily === self::OS_FAMILY_WINDOWS) {
            $str = shell_exec('wmic cpu get loadpercentage');
            if (preg_match('/LoadPercentage\s+(\d+)/', $str, $matches)) {
                $cpuUsage = (int)$matches[1];
            }
        } elseif ($this->osFamily === self::OS_FAMILY_LINUX) {
            if (function_exists('shell_exec')) {
                $str = shell_exec('top -bn1 | grep "Cpu(s)" | sed "s/.*, *\([0-9.]*\)%* id.*/\1/" | awk \'{print 100 - $1}\'');
                $cpuUsage = (int)$str;
            }else{
                $cpuUsage = -1;
            }
        }

        return $cpuUsage;
    }

    #[Override] protected function getTitle(): string
    {
        return "CPU Load";
    }

    #[Override] protected function getValue(): string
    {
        return $this->loadPercent . '%';
    }

    #[Override] protected function getColor(): int
    {
        if ($this->loadPercent < 50) {
            return StatWidget::COLOR_SUCCESS;
        }

        if ($this->loadPercent < 80) {
            return StatWidget::COLOR_WARNING;
        }

        return StatWidget::COLOR_DANGER;
    }
}
