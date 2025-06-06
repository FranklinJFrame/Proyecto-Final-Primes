<div>
    <div id="uploadthing-widget"></div>
    <div class="mt-2">
        <ul id="imagenes-list" class="list-disc pl-5 text-xs text-gray-400"></ul>
    </div>
    <input type="hidden" id="imagenes_uploadthing" />
</div>

<script src="https://js.uploadthing.com/widget.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.uploadthingWidgetInitialized) return;
        window.uploadthingWidgetInitialized = true;

        const widget = window.uploadthing.createWidget({
            publicKey: 'eyJhcGlLZXkiOiJza19saXZlXzdhODhlZTVlYTQxMWU1ZjcyYzBhYzFiMGUwYjU4MWYxYjk5OWU4YzUwNjU4YmIwYTAzYjc4YzM0YWI3MThkZDkiLCJhcHBJZCI6Imo1a296cnBneGwiLCJyZWdpb25zIjpbInNlYTEiXX0=', // Pega aquí tu public key de UploadThing
            maxFileCount: 5,
            onUploadComplete: (files) => {
                // files es un array de objetos con .url
                const urls = files.map(f => f.url);
                // Actualiza el textarea oculto de Filament
                const textarea = document.querySelector('textarea[name="imagenes"]');
                if (textarea) {
                    textarea.value = JSON.stringify(urls);
                    textarea.dispatchEvent(new Event('input', { bubbles: true }));
                }
                // Muestra las URLs subidas
                const ul = document.getElementById('imagenes-list');
                ul.innerHTML = '';
                urls.forEach(url => {
                    const li = document.createElement('li');
                    li.innerHTML = `<a href="${url}" target="_blank">${url}</a>`;
                    ul.appendChild(li);
                });
            }
        });
        widget.mount('#uploadthing-widget');
    });
</script> 