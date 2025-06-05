#!/bin/bash

# Verificar si el directorio de almacenamiento existe
if [ ! -d "storage/app/public" ]; then
    echo "Creando directorio de almacenamiento..."
    mkdir -p storage/app/public
fi

# Establecer permisos recursivamente
chmod -R 777 storage/app/public

# Crear enlace simbólico si no existe
if [ ! -L "public/storage" ]; then
    echo "Creando enlace simbólico..."
    php artisan storage:link
fi

# Verificar si el enlace simbólico se creó correctamente
if [ -L "public/storage" ]; then
    echo "Enlace simbólico creado exitosamente"
else
    echo "Error: No se pudo crear el enlace simbólico"
fi