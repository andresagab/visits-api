<?php

namespace App\Console\Commands\Visits;

use App\Models\Visit;
use Exception;
use Illuminate\Console\Command;
use function Laravel\Prompts\form;

class CreateVisit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visit:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear una nueva visita con coordenadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        # solicitar datos
        $responses = form()
            ->text("Ingresa el nombre del cliente:",
                placeholder: "Nombres y apellidos",
                validate: [
                    'required',
                    'max:200',
                ],
                name: "name",
            )
            ->text("Ingresa el email:",
                placeholder: "test@example.com",
                validate: [
                    'required',
                    'email',
                    'max:255',
                ],
                name: "email",
            )
            ->text('Ingresa la latitud de la visita:',
                placeholder: "6.249781",
                validate: [
                    'required',
                    'numeric',
                    'decimal:1,7'
                ],
                name: "latitude",
            )
            ->text('Ingresa la longitud de la visita:',
                placeholder: "-75.568570",
                validate: [
                    'required',
                    'numeric',
                    'decimal:1,7'
                ],
                name: "longitude",
            )
            ->submit();

        # crear registro
        try {
            $visit = Visit::create($responses);
            $this->info("Visita #{$visit->id} creada exitosamente.");
        }
        catch (Exception $exception)
        {
            $this->error("Error al crear la visita: {$exception->getMessage()}");
        }

        return 0;
    }
}
