<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Danack\Console\Tests\Descriptor;

use Danack\Console\Descriptor\TextDescriptor;

class TextDescriptorTest extends AbstractDescriptorTest
{
    protected function getDescriptor()
    {
        return new TextDescriptor();
    }

    protected function getFormat()
    {
        return 'txt';
    }
}
