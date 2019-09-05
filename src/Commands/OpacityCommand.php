<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\BlendMode;

class OpacityCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $transparency = $this->argument(0)
            ->between(0, 100)
            ->required()
            ->value() / 100;

        return $this->handleCommand(function () use ($image, $transparency) {
            $core = $image->getCore();

            if ( ! $core->hasAlpha()) {
                $background = $image->getDriver()->newImage($core->width, $core->height, [0, 0, 0, 0])->getCore();

                $core = $background->composite([$background, $core], BlendMode::OVER);
            }

            $core = $core->multiply([1.0, 1.0, 1.0, $transparency]);

            $image->setCore($core);
        });
    }
}
