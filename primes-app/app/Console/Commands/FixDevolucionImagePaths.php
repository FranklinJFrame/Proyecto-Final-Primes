<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Devolucion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Fachada File para operaciones de directorio

class FixDevolucionImagePaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devoluciones:fix-image-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mueve las imágenes de devoluciones de la ruta incorrecta (storage/app/public/public/devoluciones) a la correcta (storage/app/public/devoluciones).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando la corrección de rutas de imágenes de devoluciones...');

        // Directorio base incorrecto donde Laravel podría haber estado guardando las imágenes
        // debido al doble 'public' en la ruta de store original.
        $baseIncorrectPath = storage_path('app/public/public/devoluciones');

        // Directorio base correcto donde las imágenes deberían estar.
        $baseCorrectPath = storage_path('app/public/devoluciones');

        if (!File::isDirectory($baseIncorrectPath)) {
            $this->info('El directorio incorrecto base ' . $baseIncorrectPath . ' no existe. No hay nada que mover.');
            return 0;
        }

        // Obtener todos los subdirectorios en el baseIncorrectPath (que deberían ser los IDs de devolución)
        $devolucionDirs = File::directories($baseIncorrectPath);
        $archivosMovidos = 0;
        $errores = 0;

        foreach ($devolucionDirs as $incorrectDevolucionDir) {
            $devolucionId = basename($incorrectDevolucionDir);
            $correctDevolucionDir = $baseCorrectPath . DIRECTORY_SEPARATOR . $devolucionId;

            // Asegurarse de que el directorio de destino exista
            if (!File::isDirectory($correctDevolucionDir)) {
                File::makeDirectory($correctDevolucionDir, 0755, true, true);
                $this->line("Directorio creado: {$correctDevolucionDir}");
            }

            // Mover todos los archivos del directorio incorrecto al correcto
            $filesInDir = File::files($incorrectDevolucionDir);
            foreach ($filesInDir as $file) {
                $fileName = $file->getFilename();
                $destinationPath = $correctDevolucionDir . DIRECTORY_SEPARATOR . $fileName;

                try {
                    if (File::move($file->getPathname(), $destinationPath)) {
                        $this->line("Movido: " . $file->getPathname() . " -> " . $destinationPath);
                        $archivosMovidos++;
                    } else {
                        $this->error("Error al mover: " . $file->getPathname());
                        $errores++;
                    }
                } catch (\Exception $e) {
                    $this->error("Excepción al mover " . $file->getPathname() . ": " . $e->getMessage());
                    $errores++;
                }
            }

            // Si el directorio incorrecto está vacío después de mover, eliminarlo
            if (count(File::files($incorrectDevolucionDir)) === 0 && count(File::directories($incorrectDevolucionDir)) === 0) {
                File::deleteDirectory($incorrectDevolucionDir);
                $this->line("Directorio incorrecto eliminado: {$incorrectDevolucionDir}");
            }
        }

        if ($archivosMovidos > 0) {
            $this->info("Proceso completado. Archivos movidos: {$archivosMovidos}. Errores: {$errores}.");
            $this->info("Recuerda que las rutas en la BD para 'imagenes_adjuntas' no necesitan cambiarse.");
            $this->info("Asegúrate de que 'php artisan storage:link' se haya ejecutado y funcione correctamente.");
            $this->info("Prueba creando una nueva devolución y verificando una existente.");
        } else if ($errores > 0) {
            $this->error("Proceso completado con errores. Archivos movidos: {$archivosMovidos}. Errores: {$errores}.");
        }
        else {
            $this->info('No se encontraron archivos en la estructura de directorios incorrecta para mover.');
        }

        return 0;
    }
}
