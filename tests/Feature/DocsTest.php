<?php

namespace Tests\Feature;

use Tests\TestCase;

class DocsTest extends TestCase
{
    public function test_authentication_documentation_is_rendered(): void
    {
        $this->get('/docs/authentication')
            ->assertOk()
            ->assertSee('Obtener un token de acceso')
            ->assertSee('/api/v1/auth/token');
    }
}
