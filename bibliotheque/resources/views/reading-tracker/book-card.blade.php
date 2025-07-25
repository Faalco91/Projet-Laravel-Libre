<div class="book-card bg-white border rounded-lg p-3 mb-3 shadow-sm cursor-move hover:shadow-md transition-all duration-200" 
     draggable="true" 
     data-reading-status-id="{{ $item->id }}">
    
    <!-- En-tête du livre -->
    <div class="flex justify-between items-start mb-2">
        <h4 class="font-bold text-sm leading-tight flex-1 mr-2">{{ $item->book->titre }}</h4>
        <button onclick="removeFromTracker({{ $item->id }})" 
                class="text-red-500 hover:text-red-700 text-xs opacity-60 hover:opacity-100"
                title="Retirer du suivi">
            ✖️
        </button>
    </div>
    
    <!-- Auteur -->
    <p class="text-xs text-gray-600 mb-3">{{ $item->book->auteur }}</p>
    
    <!-- Progression (seulement pour en cours) -->
    @if($item->status === 'en_cours')
        <div class="mb-3">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs text-gray-500">Progression</span>
                <span class="text-xs font-bold">{{ $item->progression }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ $item->progression }}%"></div>
            </div>
            <input type="range" 
                   min="0" 
                   max="100" 
                   value="{{ $item->progression }}"
                   class="w-full mt-2"
                   onchange="updateProgress({{ $item->id }}, this.value)"
                   onclick="event.stopPropagation()">
        </div>
    @endif

    <!-- Dates -->
    @if($item->date_debut || $item->date_fin)
        <div class="text-xs text-gray-500 mb-3 space-y-1">
            @if($item->date_debut)
                <div class="flex items-center">
                    <span class="mr-1">📅</span>
                    <span>Début: {{ $item->date_debut->format('d/m/y') }}</span>
                </div>
            @endif
            
            @if($item->date_fin)
                <div class="flex items-center">
                    <span class="mr-1">🏁</span>
                    <span>Fin: {{ $item->date_fin->format('d/m/y') }}</span>
                </div>
            @endif
            
            @if($item->status === 'termine' && $item->date_debut && $item->date_fin)
                @php
                    $duree = $item->date_debut->diffInDays($item->date_fin);
                @endphp
                <div class="flex items-center">
                    <span class="mr-1">⏱️</span>
                    <span>Durée: {{ $duree }} jour{{ $duree > 1 ? 's' : '' }}</span>
                </div>
            @endif
        </div>
    @endif

    <!-- Statut visuel -->
    @if($item->status === 'termine')
        <div class="mb-3 flex items-center text-xs text-green-600 bg-green-50 p-2 rounded">
            <span class="mr-1">✅</span>
            <span>Terminé à 100%</span>
        </div>
    @endif

    @if($item->status === 'abandonne' && $item->progression > 0)
        <div class="mb-3 flex items-center text-xs text-red-600 bg-red-50 p-2 rounded">
            <span class="mr-1">❌</span>
            <span>Abandonné à {{ $item->progression }}%</span>
        </div>
    @endif

    <!-- Actions rapides -->
    <div class="flex gap-2">
        <a href="{{ route('livre.show', $item->book->id) }}" 
           class="text-xs bg-blue-500 text-white px-3 py-1.5 rounded hover:bg-blue-600 flex-1 text-center"
           onclick="event.stopPropagation()">
            👁️ Voir
        </a>
        
        @if($item->status === 'a_lire')
            <button onclick="quickUpdateStatus({{ $item->id }}, 'en_cours')"
                    class="text-xs bg-yellow-500 text-white px-3 py-1.5 rounded hover:bg-yellow-600 flex-1"
                    onclick="event.stopPropagation()">
                ▶️ Commencer
            </button>
        @endif
        
        @if($item->status === 'en_cours')
            <button onclick="quickUpdateStatus({{ $item->id }}, 'termine')"
                    class="text-xs bg-green-500 text-white px-3 py-1.5 rounded hover:bg-green-600 flex-1"
                    onclick="event.stopPropagation()">
                ✅ Terminer
            </button>
        @endif
    </div>
</div>

<script>
function quickUpdateStatus(readingStatusId, newStatus) {
    fetch('{{ route("reading-tracker.update-status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            reading_status_id: readingStatusId,
            new_status: newStatus,
            new_order: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur de connexion');
    });
}
</script>