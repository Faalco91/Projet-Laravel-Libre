<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">📚 Suivi de lecture</h2>
    </x-slot>

    <div class="py-4">
        <div class="w-full px-4">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Navigation -->
            <div class="mb-4 flex flex-wrap gap-2">
                <a href="{{ route('dashboard') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ← Retour au tableau de bord
                </a>
                <a href="{{ route('books.index') }}" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    📚 Tous les livres
                </a>
                <button id="add-book-btn" style="background-color: #059669; color: white; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer;">
                    ➕ Ajouter un livre au suivi
                </button>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-4 gap-4 mb-4" id="stats-container">
                <div class="bg-blue-100 p-3 rounded-lg text-center">
                    <h4 class="font-bold text-blue-800 text-sm">À lire</h4>
                    <p class="text-xl font-bold text-blue-600">{{ $columns['a_lire']->count() }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg text-center">
                    <h4 class="font-bold text-yellow-800 text-sm">En cours</h4>
                    <p class="text-xl font-bold text-yellow-600">{{ $columns['en_cours']->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg text-center">
                    <h4 class="font-bold text-green-800 text-sm">Terminés</h4>
                    <p class="text-xl font-bold text-green-600">{{ $columns['termine']->count() }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg text-center">
                    <h4 class="font-bold text-red-800 text-sm">Abandonnés</h4>
                    <p class="text-xl font-bold text-red-600">{{ $columns['abandonne']->count() }}</p>
                </div>
            </div>

            <!-- Colonnes de drag & drop - PLEINE LARGEUR avec scroll optimisé -->
            <div class="flex gap-3 overflow-x-auto" style="min-height: 600px;">
                <!-- Colonne À lire -->
                <div class="flex-1 min-w-[300px] bg-blue-50 border-2 border-blue-200 rounded-lg flex flex-col" style="height: 600px;">
                    <div class="bg-blue-200 p-3 rounded-t-lg flex-shrink-0">
                        <h3 class="font-bold text-blue-800 text-center text-sm">
                            📖 À lire ({{ $columns['a_lire']->count() }})
                        </h3>
                    </div>
                    <div class="drop-zone p-3 flex-1 overflow-y-auto" data-status="a_lire" id="column-a_lire">
                        @foreach($columns['a_lire'] as $item)
                            @include('reading-tracker.book-card', ['item' => $item])
                        @endforeach
                        @if($columns['a_lire']->count() === 0)
                            <div class="text-center text-blue-400 text-sm mt-8">
                                <div class="text-4xl mb-4">📖</div>
                                <p>Glissez vos livres<br>à lire ici</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Colonne En cours -->
                <div class="flex-1 min-w-[300px] bg-yellow-50 border-2 border-yellow-200 rounded-lg flex flex-col" style="height: 600px;">
                    <div class="bg-yellow-200 p-3 rounded-t-lg flex-shrink-0">
                        <h3 class="font-bold text-yellow-800 text-center text-sm">
                            📚 En cours ({{ $columns['en_cours']->count() }})
                        </h3>
                    </div>
                    <div class="drop-zone p-3 flex-1 overflow-y-auto" data-status="en_cours" id="column-en_cours">
                        @foreach($columns['en_cours'] as $item)
                            @include('reading-tracker.book-card', ['item' => $item])
                        @endforeach
                        @if($columns['en_cours']->count() === 0)
                            <div class="text-center text-yellow-400 text-sm mt-8">
                                <div class="text-4xl mb-4">📚</div>
                                <p>Glissez vos livres<br>en cours ici</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Colonne Terminé -->
                <div class="flex-1 min-w-[300px] bg-green-50 border-2 border-green-200 rounded-lg flex flex-col" style="height: 600px;">
                    <div class="bg-green-200 p-3 rounded-t-lg flex-shrink-0">
                        <h3 class="font-bold text-green-800 text-center text-sm">
                            ✅ Terminés ({{ $columns['termine']->count() }})
                        </h3>
                    </div>
                    <div class="drop-zone p-3 flex-1 overflow-y-auto" data-status="termine" id="column-termine">
                        @foreach($columns['termine'] as $item)
                            @include('reading-tracker.book-card', ['item' => $item])
                        @endforeach
                        @if($columns['termine']->count() === 0)
                            <div class="text-center text-green-400 text-sm mt-8">
                                <div class="text-4xl mb-4">✅</div>
                                <p>Glissez vos livres<br>terminés ici</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Colonne Abandonné -->
                <div class="flex-1 min-w-[300px] bg-red-50 border-2 border-red-200 rounded-lg flex flex-col" style="height: 600px;">
                    <div class="bg-red-200 p-3 rounded-t-lg flex-shrink-0">
                        <h3 class="font-bold text-red-800 text-center text-sm">
                            ❌ Abandonnés ({{ $columns['abandonne']->count() }})
                        </h3>
                    </div>
                    <div class="drop-zone p-3 flex-1 overflow-y-auto" data-status="abandonne" id="column-abandonne">
                        @foreach($columns['abandonne'] as $item)
                            @include('reading-tracker.book-card', ['item' => $item])
                        @endforeach
                        @if($columns['abandonne']->count() === 0)
                            <div class="text-center text-red-400 text-sm mt-8">
                                <div class="text-4xl mb-4">❌</div>
                                <p>Glissez vos livres<br>abandonnés ici</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter un livre -->
    <div id="add-book-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold mb-4">Ajouter un livre au suivi</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Livre</label>
                <select id="book-select" class="w-full p-2 border border-gray-300 rounded">
                    <option value="">Sélectionner un livre</option>
                    @foreach($booksNotTracked as $book)
                        <option value="{{ $book->id }}">{{ $book->titre }} - {{ $book->auteur }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut initial</label>
                <select id="status-select" class="w-full p-2 border border-gray-300 rounded">
                    <option value="a_lire">📖 À lire</option>
                    <option value="en_cours">📚 En cours</option>
                    <option value="termine">✅ Terminé</option>
                    <option value="abandonne">❌ Abandonné</option>
                </select>
            </div>
            
            <div class="flex gap-4">
                <button id="confirm-add-book" class="flex-1 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Ajouter
                </button>
                <button id="cancel-add-book" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Annuler
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Permettre le scroll de la page entière */
        body {
            overflow: auto;
        }
        
        .book-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .book-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .book-card.dragging {
            opacity: 0.7;
            transform: rotate(3deg) scale(1.02);
            z-index: 1000;
        }
        
        .drop-zone {
            transition: all 0.2s ease;
            /* Scroll optimisé */
            scroll-behavior: smooth;
        }
        
        .drop-zone.drag-over {
            background-color: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
            border-style: dashed;
        }

        /* Scrollbar améliorée et plus visible */
        .drop-zone::-webkit-scrollbar {
            width: 8px;
        }
        
        .drop-zone::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
            border-radius: 4px;
            margin: 4px;
        }
        
        .drop-zone::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .drop-zone::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.4);
        }

        /* Indicateur de scroll pour les colonnes avec beaucoup de contenu */
        .drop-zone {
            position: relative;
        }

        .drop-zone::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 8px;
            height: 20px;
            background: linear-gradient(transparent, rgba(0,0,0,0.1));
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .drop-zone:hover::after {
            opacity: 1;
        }

        /* Colonnes flexibles avec hauteur optimisée */
        .flex-col {
            display: flex;
            flex-direction: column;
        }
        
        .flex-1 {
            flex: 1;
        }
        
        .flex-shrink-0 {
            flex-shrink: 0;
        }
        
        .min-w-[300px] {
            min-width: 300px;
        }

        /* Responsive - sur petit écran, colonnes empilées */
        @media (max-width: 1024px) {
            .flex {
                flex-direction: column !important;
                height: auto !important;
            }
            
            .min-w-[300px] {
                min-width: 100% !important;
            }
            
            .drop-zone {
                max-height: 400px !important;
            }

            /* Supprimer l'indicateur de scroll sur mobile */
            .drop-zone::after {
                display: none;
            }
        }

        /* Animation pour le conteneur principal */
        .flex.gap-3 {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Optimisation de la popup */
        #add-book-modal {
            backdrop-filter: blur(4px);
        }
        
        #add-book-modal .bg-white {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Amélioration des transitions lors du drag & drop */
        .book-card {
            will-change: transform;
        }

        /* Style pour les colonnes vides */
        .drop-zone:empty::before {
            content: 'Glissez vos livres ici';
            display: block;
            text-align: center;
            color: #9ca3af;
            font-style: italic;
            margin-top: 2rem;
        }
    </style>

    <script>
        let draggedElement = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
            initializeModal();
        });

        function initializeDragAndDrop() {
            // Faire tous les livres draggables
            document.querySelectorAll('.book-card').forEach(card => {
                card.addEventListener('dragstart', handleDragStart);
                card.addEventListener('dragend', handleDragEnd);
            });

            // Configurer les zones de drop
            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.addEventListener('dragover', handleDragOver);
                zone.addEventListener('drop', handleDrop);
                zone.addEventListener('dragenter', handleDragEnter);
                zone.addEventListener('dragleave', handleDragLeave);
            });
        }

        function handleDragStart(e) {
            draggedElement = this;
            this.classList.add('dragging');
        }

        function handleDragEnd(e) {
            this.classList.remove('dragging');
            document.querySelectorAll('.drop-zone').forEach(zone => {
                zone.classList.remove('drag-over');
            });
        }

        function handleDragOver(e) {
            e.preventDefault();
        }

        function handleDragEnter(e) {
            this.classList.add('drag-over');
        }

        function handleDragLeave(e) {
            if (!this.contains(e.relatedTarget)) {
                this.classList.remove('drag-over');
            }
        }

        function handleDrop(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            
            if (draggedElement) {
                const newStatus = this.dataset.status;
                const readingStatusId = draggedElement.dataset.readingStatusId;
                const currentColumn = draggedElement.closest('.drop-zone');
                
                // Si on change de colonne
                if (currentColumn !== this) {
                    updateBookStatus(readingStatusId, newStatus, this);
                }
            }
        }

        function updateBookStatus(readingStatusId, newStatus, targetZone) {
            fetch('{{ route("reading-tracker.update-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    reading_status_id: readingStatusId,
                    new_status: newStatus,
                    new_order: targetZone.children.length + 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Déplacer l'élément visuellement
                    targetZone.appendChild(draggedElement);
                    
                    // Mettre à jour les compteurs en temps réel
                    updateColumnCounters();
                    
                    // Afficher un message de succès
                    showNotification('Statut mis à jour avec succès!', 'success');
                } else {
                    showNotification('Erreur lors de la mise à jour', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Erreur de connexion', 'error');
            });
        }

        function updateColumnCounters() {
            // Compter les livres dans chaque colonne
            const columns = {
                'a_lire': document.querySelectorAll('#column-a_lire .book-card').length,
                'en_cours': document.querySelectorAll('#column-en_cours .book-card').length,
                'termine': document.querySelectorAll('#column-termine .book-card').length,
                'abandonne': document.querySelectorAll('#column-abandonne .book-card').length
            };

            // Mettre à jour les en-têtes des colonnes
            document.querySelector('#column-a_lire').previousElementSibling.querySelector('h3').innerHTML = 
                '📖 À lire (' + columns.a_lire + ')';
            document.querySelector('#column-en_cours').previousElementSibling.querySelector('h3').innerHTML = 
                '📚 En cours (' + columns.en_cours + ')';
            document.querySelector('#column-termine').previousElementSibling.querySelector('h3').innerHTML = 
                '✅ Terminés (' + columns.termine + ')';
            document.querySelector('#column-abandonne').previousElementSibling.querySelector('h3').innerHTML = 
                '❌ Abandonnés (' + columns.abandonne + ')';

            // Mettre à jour les statistiques en haut
            const statsElements = document.querySelectorAll('#stats-container .text-xl');
            if (statsElements[0]) statsElements[0].textContent = columns.a_lire;
            if (statsElements[1]) statsElements[1].textContent = columns.en_cours;
            if (statsElements[2]) statsElements[2].textContent = columns.termine;
            if (statsElements[3]) statsElements[3].textContent = columns.abandonne;

            // Gestion des messages "zone vide"
            updateEmptyMessages();
        }

        function updateEmptyMessages() {
            const columns = ['a_lire', 'en_cours', 'termine', 'abandonne'];
            const emptyMessages = {
                'a_lire': '<div class="text-center text-blue-400 text-sm mt-8"><div class="text-4xl mb-4">📖</div><p>Glissez vos livres<br>à lire ici</p></div>',
                'en_cours': '<div class="text-center text-yellow-400 text-sm mt-8"><div class="text-4xl mb-4">📚</div><p>Glissez vos livres<br>en cours ici</p></div>',
                'termine': '<div class="text-center text-green-400 text-sm mt-8"><div class="text-4xl mb-4">✅</div><p>Glissez vos livres<br>terminés ici</p></div>',
                'abandonne': '<div class="text-center text-red-400 text-sm mt-8"><div class="text-4xl mb-4">❌</div><p>Glissez vos livres<br>abandonnés ici</p></div>'
            };

            columns.forEach(status => {
                const column = document.getElementById('column-' + status);
                const bookCards = column.querySelectorAll('.book-card');
                const existingEmptyMessage = column.querySelector('.text-center');

                if (bookCards.length === 0) {
                    // Ajouter le message vide si pas de livres
                    if (!existingEmptyMessage) {
                        column.innerHTML = emptyMessages[status];
                    }
                } else {
                    // Supprimer le message vide s'il y a des livres
                    if (existingEmptyMessage && existingEmptyMessage.parentElement === column) {
                        existingEmptyMessage.remove();
                    }
                }
            });
        }

        function initializeModal() {
            const modal = document.getElementById('add-book-modal');
            const addBtn = document.getElementById('add-book-btn');
            const confirmBtn = document.getElementById('confirm-add-book');
            const cancelBtn = document.getElementById('cancel-add-book');

            addBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                resetModal();
            });

            confirmBtn.addEventListener('click', () => {
                const bookId = document.getElementById('book-select').value;
                const status = document.getElementById('status-select').value;
                
                if (!bookId) {
                    showNotification('Veuillez sélectionner un livre', 'error');
                    return;
                }
                
                addBookToTracker(bookId, status);
            });

            // Fermer en cliquant à l'extérieur
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    resetModal();
                }
            });
        }

        function addBookToTracker(bookId, status) {
            fetch('{{ route("reading-tracker.add-book") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    book_id: bookId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Fermer la popup
                    const modal = document.getElementById('add-book-modal');
                    modal.classList.add('hidden');
                    resetModal();
                    
                    // Afficher un message de succès
                    showNotification('Livre ajouté au suivi avec succès!', 'success');
                    
                    // Recharger la page après un court délai
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification(data.error || 'Erreur lors de l\'ajout', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Erreur de connexion', 'error');
            });
        }

        function resetModal() {
            document.getElementById('book-select').value = '';
            document.getElementById('status-select').value = 'a_lire';
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Gestion des progressions
        function updateProgress(readingStatusId, newProgress) {
            fetch('{{ route("reading-tracker.update-progress") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    reading_status_id: readingStatusId,
                    progression: newProgress
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Progression mise à jour!', 'success');
                    // Si progression = 100%, déplacer vers "Terminé"
                    if (newProgress == 100) {
                        setTimeout(() => location.reload(), 1000);
                    }
                } else {
                    showNotification('Erreur lors de la mise à jour', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Erreur de connexion', 'error');
            });
        }

        // Supprimer un livre du suivi
        function removeFromTracker(readingStatusId) {
            if (!confirm('Êtes-vous sûr de vouloir retirer ce livre du suivi ?')) {
                return;
            }

            fetch('{{ route("reading-tracker.remove-book") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    reading_status_id: readingStatusId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    showNotification('Erreur lors de la suppression', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Erreur de connexion', 'error');
            });
        }
    </script>
</x-app-layout>