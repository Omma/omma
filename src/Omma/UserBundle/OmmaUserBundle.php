<?php

namespace Omma\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OmmaUserBundle extends Bundle
{
    public function getParent()
    {
        return "SonataUserBundle";
    }
}
