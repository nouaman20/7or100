
@extends('dashboard')

@section('title', 'Catégories | MielNature')

@section('content')
<div class="mn-categories min-h-full">

    {{-- Page heading --}}
    <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                <span>Administration</span>
                <span>/</span>
                <span class="font-semibold text-amber-600">Catégories</span>
            </div>

            <h1 class="text-2xl font-extrabold tracking-tight text-[#203e24] sm:text-3xl">
                Gestion des catégories
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                Organisez vos miels et vos produits naturels.
            </p>
        </div>

        <button
            type="button"
            onclick="openCreateModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#ffbd2f] px-5 py-3 text-sm font-extrabold text-[#20351e] shadow-sm transition hover:-translate-y-0.5 hover:bg-[#f5b01c] hover:shadow-md focus:outline-none focus:ring-4 focus:ring-amber-200"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
            </svg>
            Nouvelle catégorie
        </button>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800"
             role="status">
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path d="m5 12 4 4L19 6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
            <p class="mb-2 font-bold">Veuillez vérifier les informations saisies :</p>
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Summary cards --}}
    <div class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

        <div class="flex items-center gap-4 rounded-2xl border border-[#eee8d9] bg-white p-5 shadow-sm">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-amber-50 text-amber-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.7">
                    <path d="m12 3 9 5-9 5-9-5 9-5Z" stroke-linejoin="round"/>
                    <path d="m3 12 9 5 9-5M3 16l9 5 9-5" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total des catégories</p>
                <p class="mt-1 text-2xl font-extrabold text-[#203e24]">
                    {{ number_format($totalCategories ?? $categories->total()) }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-2xl border border-[#eee8d9] bg-white p-5 shadow-sm">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-green-50 text-green-700">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.7">
                    <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"
                          stroke-linejoin="round"/>
                    <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Catégories actives</p>
                <p class="mt-1 text-2xl font-extrabold text-[#203e24]">
                    {{ number_format($activeCategories ?? 0) }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4 rounded-2xl border border-[#eee8d9] bg-white p-5 shadow-sm sm:col-span-2 xl:col-span-1">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gray-100 text-gray-600">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.7">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M8 12h8" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Catégories inactives</p>
                <p class="mt-1 text-2xl font-extrabold text-[#203e24]">
                    {{ number_format($inactiveCategories ?? 0) }}
                </p>
            </div>
        </div>

    </div>

    {{-- Main listing card --}}
    <section class="overflow-hidden rounded-2xl border border-[#eee8d9] bg-white shadow-sm">

        {{-- Search and filters --}}
        <div class="flex flex-col gap-4 border-b border-[#f0ece3] p-5 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <h2 class="text-lg font-extrabold text-[#203e24]">Toutes les catégories</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Consultez et gérez les catégories de votre boutique.
                </p>
            </div>

            <form method="GET"
                  action="{{ route('dashboard.categories.index') }}"
                  class="flex flex-col gap-3 sm:flex-row">

                <div class="relative">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                         viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m16 16 4 4" stroke-linecap="round"/>
                    </svg>

                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Rechercher une catégorie..."
                        class="w-full rounded-xl border border-[#e9e5dc] bg-[#fffefa] py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100 sm:w-64"
                    >
                </div>

                <select
                    name="status"
                    class="rounded-xl border border-[#e9e5dc] bg-[#fffefa] px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
                >
                    <option value="">Tous les statuts</option>
                    <option value="1" @selected(request('status') === '1')>Actives</option>
                    <option value="0" @selected(request('status') === '0')>Inactives</option>
                </select>

                <button type="submit"
                        class="rounded-xl border border-[#e9e5dc] px-4 py-2.5 text-sm font-bold text-[#203e24] transition hover:bg-amber-50">
                    Filtrer
                </button>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('dashboard.categories.index') }}"
                       class="rounded-xl px-3 py-2.5 text-center text-sm font-semibold text-gray-500 hover:bg-gray-50">
                        Réinitialiser
                    </a>
                @endif
            </form>
        </div>

        {{-- Responsive category list --}}
        <div class="overflow-x-auto">
            <table class="w-full min-w-[780px] text-left">
                <thead class="bg-[#fcfaf5]">
                    <tr class="text-xs font-bold uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4">Date de création</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#f1eee7]">
                    @forelse($categories as $category)
                        <tr class="transition hover:bg-[#fffdf8]">

                            {{-- Image and category name --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="grid h-12 w-12 shrink-0 place-items-center overflow-hidden rounded-xl border border-[#eee8d9] bg-[#fff8e7]">
                                        @if($category->image)
                                            <img
                                                src="{{ asset('storage/' . $category->image) }}"
                                                alt="{{ $category->name }}"
                                                class="h-full w-full object-cover"
                                                loading="lazy"
                                            >
                                        @else
                                            <svg class="h-6 w-6 text-amber-500"
                                                 viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.6">
                                                <path d="M12 3 20 8v8l-8 5-8-5V8l8-5Z"
                                                      stroke-linejoin="round"/>
                                                <path d="m4 8 8 5 8-5M12 13v8"
                                                      stroke-linejoin="round"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-bold text-[#263b28]">
                                            {{ $category->name }}
                                        </p>

                                        <p class="mt-1 max-w-xs truncate text-xs text-gray-500">
                                            {{ $category->description ?: 'Aucune description' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="rounded-lg bg-gray-50 px-2.5 py-1.5 font-mono text-xs text-gray-600">
                                    {{ $category->slug }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if($category->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-bold text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-bold text-gray-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $category->created_at?->format('d/m/Y') ?? '—' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Edit --}}
                                    <button
                                        type="button"
                                        title="Modifier"
                                        aria-label="Modifier {{ $category->name }}"
                                        data-category="{{ json_encode([
                                            'id' => $category->id,
                                            'name' => $category->name,
                                            'slug' => $category->slug,
                                            'description' => $category->description,
                                            'is_active' => (bool) $category->is_active,
                                            'image_url' => $category->image ? asset('storage/' . $category->image) : '',
                                        ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}"
                                        onclick="openEditModal(this)"
                                        class="grid h-9 w-9 place-items-center rounded-xl border border-[#eee8d9] text-gray-600 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.8">
                                            <path d="m14 5 5 5M4 20l4.5-1 11-11a2.12 2.12 0 0 0-3-3l-11 11L4 20Z"
                                                  stroke-linejoin="round"/>
                                        </svg>
                                    </button>

                                    {{-- Delete --}}
                                    <button
                                        type="button"
                                        title="Supprimer"
                                        aria-label="Supprimer {{ $category->name }}"
                                        data-delete-url="{{ route('dashboard.categories.destroy', $category->id) }}"
                                        data-delete-name="{{ $category->name }}"
                                        onclick="openDeleteModal(this)"
                                        class="grid h-9 w-9 place-items-center rounded-xl border border-red-100 text-red-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.8">
                                            <path d="M3 6h18M8 6V4h8v2m-11 0 1 14h12l1-14M10 10v6m4-6v6"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto mb-4 grid h-16 w-16 place-items-center rounded-2xl bg-amber-50 text-amber-600">
                                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.5">
                                        <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
                                        <path d="m3 12 9 5 9-5M3 16l9 5 9-5"/>
                                    </svg>
                                </div>
                                <h3 class="font-extrabold text-[#203e24]">
                                    Aucune catégorie trouvée
                                </h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    Essayez de modifier vos filtres ou créez une nouvelle catégorie.
                                </p>
                                <button type="button" onclick="openCreateModal()"
                                        class="mt-4 font-bold text-amber-700 hover:text-amber-800">
                                    + Créer une catégorie
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($categories->hasPages() || $categories->total() > 0)
            <div class="flex flex-col gap-4 border-t border-[#f0ece3] px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500">
                    Affichage de
                    <span class="font-bold text-gray-700">{{ $categories->firstItem() ?? 0 }}</span>
                    à
                    <span class="font-bold text-gray-700">{{ $categories->lastItem() ?? 0 }}</span>
                    sur
                    <span class="font-bold text-gray-700">{{ $categories->total() }}</span>
                    catégories
                </p>

                <div class="mn-pagination">
                    {{ $categories->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </section>
</div>


{{-- CREATE / EDIT MODAL --}}
<div id="categoryModal"
     class="mn-modal fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-[#17251b]/50 p-4 backdrop-blur-sm"
     aria-hidden="true">

    <div class="mn-modal-card my-auto w-full max-w-xl overflow-hidden rounded-2xl border border-[#eee8d9] bg-white shadow-2xl"
         role="dialog"
         aria-modal="true"
         aria-labelledby="categoryModalTitle">

        <div class="flex items-center justify-between border-b border-[#f0ece3] px-6 py-5">
            <div>
                <h2 id="categoryModalTitle" class="text-xl font-extrabold text-[#203e24]">
                    Nouvelle catégorie
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Renseignez les informations de votre catégorie.
                </p>
            </div>

            <button type="button" onclick="closeCategoryModal()"
                    class="grid h-9 w-9 place-items-center rounded-xl text-gray-500 transition hover:bg-gray-100"
                    aria-label="Fermer">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="m18 6-12 12M6 6l12 12" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <form id="categoryForm"
              action="{{ route('dashboard.categories.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div id="categoryMethod"></div>

            <div class="max-h-[65vh] space-y-5 overflow-y-auto p-6">

                {{-- Image preview --}}
                <div>
                    <label class="mb-2 block text-sm font-bold text-gray-700">
                        Image de la catégorie
                        <span class="font-normal text-gray-400">(facultatif)</span>
                    </label>

                    <div class="flex items-center gap-4">
                        <div class="grid h-20 w-20 shrink-0 place-items-center overflow-hidden rounded-2xl border border-dashed border-[#e6d9b7] bg-[#fffaf0]">
                            <img id="imagePreview"
                                 src=""
                                 alt="Aperçu de l'image"
                                 class="hidden h-full w-full object-cover">

                            <svg id="imagePlaceholder" class="h-8 w-8 text-amber-500"
                                 viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="m21 15-5-5L5 21"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <input
                                type="file"
                                name="image"
                                id="categoryImage"
                                accept="image/jpeg,image/png,image/webp,image/avif"
                                class="block w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-50 file:px-3 file:py-2 file:font-bold file:text-amber-800 hover:file:bg-amber-100"
                            >
                            <p class="mt-2 text-xs text-gray-400">
                                JPEG, PNG, WebP ou AVIF. Maximum 2 Mo.
                            </p>
                            <p id="imageHelp" class="mt-1 hidden text-xs text-amber-700">
                                Choisissez une nouvelle image pour remplacer l'image actuelle.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div>
                    <label for="categoryName" class="mb-2 block text-sm font-bold text-gray-700">
                        Nom de la catégorie <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="categoryName"
                        name="name"
                        type="text"
                        maxlength="255"
                        required
                        placeholder="Ex. : Miels naturels"
                        class="w-full rounded-xl border border-[#e9e5dc] bg-[#fffefa] px-4 py-3 text-sm outline-none transition placeholder:text-gray-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
                    >
                </div>

                {{-- Slug --}}
                <div>
                    <label for="categorySlug" class="mb-2 block text-sm font-bold text-gray-700">
                        Slug <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="categorySlug"
                        name="slug"
                        type="text"
                        maxlength="255"
                        required
                        placeholder="miels-naturels"
                        class="w-full rounded-xl border border-[#e9e5dc] bg-[#fffefa] px-4 py-3 font-mono text-sm outline-none transition placeholder:text-gray-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
                    >
                    <p class="mt-1.5 text-xs text-gray-400">
                        Généré automatiquement à partir du nom. Vous pouvez le modifier.
                    </p>
                </div>

                {{-- Description --}}
                <div>
                    <label for="categoryDescription" class="mb-2 block text-sm font-bold text-gray-700">
                        Description
                    </label>
                    <textarea
                        id="categoryDescription"
                        name="description"
                        rows="4"
                        placeholder="Décrivez cette catégorie..."
                        class="w-full resize-y rounded-xl border border-[#e9e5dc] bg-[#fffefa] px-4 py-3 text-sm outline-none transition placeholder:text-gray-400 focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
                    ></textarea>
                </div>

                {{-- Status --}}
                <div class="flex items-center justify-between gap-4 rounded-xl border border-[#eee8d9] bg-[#fffdf8] p-4">
                    <div>
                        <p class="text-sm font-bold text-[#203e24]">Catégorie active</p>
                        <p class="mt-1 text-xs text-gray-500">
                            Autoriser l'affichage de cette catégorie dans la boutique.
                        </p>
                    </div>

                    <label class="mn-switch shrink-0">
                        <input type="checkbox" id="categoryActive" name="is_active" value="1" checked>
                        <span class="mn-switch-track"></span>
                    </label>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-[#f0ece3] bg-[#fffefa] px-6 py-4 sm:flex-row sm:justify-end">
                <button type="button"
                        onclick="closeCategoryModal()"
                        class="rounded-xl border border-[#e9e5dc] px-5 py-3 text-sm font-bold text-gray-600 transition hover:bg-gray-100">
                    Annuler
                </button>

                <button type="submit" id="saveCategoryButton"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#ffbd2f] px-6 py-3 text-sm font-extrabold text-[#20351e] transition hover:bg-[#f5b01c] focus:outline-none focus:ring-4 focus:ring-amber-200">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span id="saveCategoryText">Créer la catégorie</span>
                </button>
            </div>
        </form>
    </div>
</div>


{{-- DELETE CONFIRMATION MODAL --}}
<div id="deleteModal"
     class="mn-modal fixed inset-0 z-[110] hidden items-center justify-center bg-[#17251b]/50 p-4 backdrop-blur-sm"
     aria-hidden="true">

    <div class="mn-modal-card w-full max-w-md rounded-2xl border border-[#eee8d9] bg-white p-6 shadow-2xl"
         role="dialog"
         aria-modal="true"
         aria-labelledby="deleteModalTitle">

        <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-red-50 text-red-600">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.7">
                <path d="M12 9v4m0 4h.01M10.3 3.9 1.9 18.2A2 2 0 0 0 3.6 21h16.8a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <h2 id="deleteModalTitle" class="text-center text-xl font-extrabold text-[#203e24]">
            Supprimer la catégorie ?
        </h2>

        <p class="mt-3 text-center text-sm leading-6 text-gray-500">
            Vous êtes sur le point de supprimer
            <strong id="deleteCategoryName" class="text-gray-800"></strong>.
            Cette action est irréversible.
        </p>

        <form id="deleteForm" method="POST" class="mt-6 flex flex-col-reverse gap-3 sm:flex-row">
            @csrf
            @method('DELETE')

            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 rounded-xl border border-[#e9e5dc] px-4 py-3 text-sm font-bold text-gray-600 hover:bg-gray-50">
                Annuler
            </button>

            <button type="submit"
                    class="flex-1 rounded-xl bg-red-600 px-4 py-3 text-sm font-extrabold text-white transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-100">
                Oui, supprimer
            </button>
        </form>
    </div>
</div>


<style>
    .mn-modal {
        display: none;
    }

    .mn-modal.is-open {
        display: flex;
        animation: mnFadeIn .18s ease-out;
    }

    .mn-modal-card {
        animation: mnSlideUp .22s ease-out;
    }

    @keyframes mnFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes mnSlideUp {
        from { opacity: 0; transform: translateY(12px) scale(.99); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .mn-switch {
        position: relative;
        display: inline-flex;
        width: 46px;
        height: 26px;
        cursor: pointer;
    }

    .mn-switch input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
    }

    .mn-switch-track {
        position: absolute;
        inset: 0;
        border-radius: 999px;
        background: #d1d5db;
        transition: background .2s;
    }

    .mn-switch-track::before {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        content: "";
        box-shadow: 0 1px 3px #00000020;
        transition: transform .2s;
    }

    .mn-switch input:checked + .mn-switch-track {
        background: #e9a900;
    }

    .mn-switch input:checked + .mn-switch-track::before {
        transform: translateX(20px);
    }

    .mn-switch input:focus-visible + .mn-switch-track {
        outline: 3px solid #f9d879;
        outline-offset: 3px;
    }

    .mn-pagination nav p {
        display: none;
    }

    .mn-pagination nav > div:first-child {
        display: none;
    }

    .mn-pagination nav > div:last-child > span,
    .mn-pagination nav > div:last-child > a {
        border-radius: 10px;
    }

    body.mn-modal-open {
        overflow: hidden;
    }

    @media (prefers-reduced-motion: reduce) {
        .mn-modal,
        .mn-modal-card,
        .mn-switch-track,
        .mn-switch-track::before {
            animation: none !important;
            transition: none !important;
        }
    }
</style>


<script>
    (() => {
        const modal = document.getElementById('categoryModal');
        const deleteModal = document.getElementById('deleteModal');
        const form = document.getElementById('categoryForm');
        const methodContainer = document.getElementById('categoryMethod');

        const nameInput = document.getElementById('categoryName');
        const slugInput = document.getElementById('categorySlug');
        const descriptionInput = document.getElementById('categoryDescription');
        const activeInput = document.getElementById('categoryActive');

        const imageInput = document.getElementById('categoryImage');
        const imagePreview = document.getElementById('imagePreview');
        const imagePlaceholder = document.getElementById('imagePlaceholder');
        const imageHelp = document.getElementById('imageHelp');

        const modalTitle = document.getElementById('categoryModalTitle');
        const saveText = document.getElementById('saveCategoryText');

        const storeUrl = @json(route('dashboard.categories.store'));
        const updateUrlTemplate = @json(route('dashboard.categories.update', ['category' => '__CATEGORY_ID__']));

        let previousFocus = null;
        let slugManuallyEdited = false;
        let editing = false;

        function showModal(element) {
            previousFocus = document.activeElement;
            element.classList.add('is-open');
            element.setAttribute('aria-hidden', 'false');
            document.body.classList.add('mn-modal-open');

            const focusable = element.querySelector(
                'input:not([type="hidden"]):not([type="file"]), textarea, select, button'
            );

            focusable?.focus();
        }

        function hideModal(element) {
            element.classList.remove('is-open');
            element.setAttribute('aria-hidden', 'true');

            if (!document.querySelector('.mn-modal.is-open')) {
                document.body.classList.remove('mn-modal-open');
            }

            previousFocus?.focus();
        }

        function resetImagePreview(url = '') {
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            imagePlaceholder.classList.remove('hidden');

            if (url) {
                imagePreview.src = url;
                imagePreview.classList.remove('hidden');
                imagePlaceholder.classList.add('hidden');
            }
        }

        function slugify(value) {
            return value
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        window.openCreateModal = function () {
            editing = false;
            slugManuallyEdited = false;

            form.reset();
            form.action = storeUrl;
            methodContainer.innerHTML = '';
            nameInput.value = '';
            slugInput.value = '';
            descriptionInput.value = '';
            activeInput.checked = true;
            imageInput.value = '';
            resetImagePreview();
            imageHelp.classList.add('hidden');

            modalTitle.textContent = 'Nouvelle catégorie';
            saveText.textContent = 'Créer la catégorie';

            showModal(modal);
        };

        window.openEditModal = function (button) {
            let category;

            try {
                category = JSON.parse(button.dataset.category);
            } catch (error) {
                console.error('Impossible de lire les données de la catégorie.', error);
                return;
            }

            editing = true;
            slugManuallyEdited = true;

            form.reset();
            form.action = updateUrlTemplate.replace(
                '__CATEGORY_ID__',
                encodeURIComponent(category.id)
            );

            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            nameInput.value = category.name ?? '';
            slugInput.value = category.slug ?? '';
            descriptionInput.value = category.description ?? '';
            activeInput.checked = Boolean(category.is_active);
            imageInput.value = '';

            resetImagePreview(category.image_url ?? '');
            imageHelp.classList.toggle('hidden', !category.image_url);

            modalTitle.textContent = 'Modifier la catégorie';
            saveText.textContent = 'Enregistrer les modifications';

            showModal(modal);
        };

        window.closeCategoryModal = function () {
            hideModal(modal);
        };

        window.openDeleteModal = function (button) {
            const deleteForm = document.getElementById('deleteForm');

            deleteForm.action = button.dataset.deleteUrl;
            document.getElementById('deleteCategoryName').textContent =
                button.dataset.deleteName ?? 'cette catégorie';

            showModal(deleteModal);
        };

        window.closeDeleteModal = function () {
            hideModal(deleteModal);
        };

        nameInput.addEventListener('input', () => {
            if (!slugManuallyEdited) {
                slugInput.value = slugify(nameInput.value);
            }
        });

        slugInput.addEventListener('input', () => {
            slugManuallyEdited = slugInput.value.trim() !== '';
        });

        slugInput.addEventListener('blur', () => {
            slugInput.value = slugify(slugInput.value);
            if (!slugInput.value) {
                slugManuallyEdited = false;
            }
        });

        imageInput.addEventListener('change', () => {
            const file = imageInput.files?.[0];

            if (!file) return;

            if (!file.type.startsWith('image/')) {
                imageInput.value = '';
                alert('Veuillez sélectionner un fichier image valide.');
                return;
            }

            const reader = new FileReader();

            reader.onload = event => {
                imagePreview.src = event.target.result;
                imagePreview.classList.remove('hidden');
                imagePlaceholder.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        });

        [modal, deleteModal].forEach(element => {
            element.addEventListener('click', event => {
                if (event.target === element) {
                    hideModal(element);
                }
            });
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                if (modal.classList.contains('is-open')) {
                    hideModal(modal);
                } else if (deleteModal.classList.contains('is-open')) {
                    hideModal(deleteModal);
                }
            }
        });

        form.addEventListener('submit', event => {
            if (!form.reportValidity()) {
                event.preventDefault();
                return;
            }

            const button = document.getElementById('saveCategoryButton');
            button.disabled = true;
            saveText.textContent = editing ? 'Enregistrement...' : 'Création...';
        });
    })();
</script>
@endsection
