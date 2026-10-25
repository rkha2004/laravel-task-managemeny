@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Pengelolaan Task</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau, atur, dan kelola seluruh task dan progres pekerjaan Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tasks.create') }}" 
               class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm px-4 py-2.5 rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Task Baru</span>
            </a>
        </div>
    </div>

    <!-- Summary Metrics / Statistic Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        <!-- All Tasks -->
        <a href="{{ route('tasks.index') }}" 
           class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-xs transition {{ empty($filters['status']) && empty($filters['overdue']) ? 'ring-2 ring-slate-900/10 border-slate-900 bg-slate-50/50' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Semua Task</span>
                <span class="p-1.5 rounded-lg bg-slate-100 text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ $stats['total'] }}</div>
        </a>

        <!-- Pending -->
        <a href="{{ route('tasks.index', ['status' => 'Pending']) }}" 
           class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-xs transition {{ ($filters['status'] ?? null) === 'Pending' ? 'ring-2 ring-slate-900/10 border-slate-900 bg-slate-50/50' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Pending</span>
                <span class="p-1.5 rounded-lg bg-slate-100 text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ $stats['pending'] }}</div>
        </a>

        <!-- In Progress -->
        <a href="{{ route('tasks.index', ['status' => 'In Progress']) }}" 
           class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-xs transition {{ ($filters['status'] ?? null) === 'In Progress' ? 'ring-2 ring-slate-900/10 border-slate-900 bg-slate-50/50' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">In Progress</span>
                <span class="p-1.5 rounded-lg bg-slate-100 text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ $stats['in_progress'] }}</div>
        </a>

        <!-- Completed -->
        <a href="{{ route('tasks.index', ['status' => 'Completed']) }}" 
           class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-xs transition {{ ($filters['status'] ?? null) === 'Completed' ? 'ring-2 ring-slate-900/10 border-slate-900 bg-slate-50/50' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Completed</span>
                <span class="p-1.5 rounded-lg bg-slate-100 text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ $stats['completed'] }}</div>
        </a>

        <!-- Overdue -->
        <a href="{{ route('tasks.index', ['overdue' => '1']) }}" 
           class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:border-slate-400 hover:shadow-xs transition {{ ($filters['overdue'] ?? null) === '1' ? 'ring-2 ring-slate-900/10 border-slate-900 bg-slate-50/50' : '' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Terlambat (Overdue)</span>
                <span class="p-1.5 rounded-lg bg-slate-100 text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ $stats['overdue'] }}</div>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Keyword Search -->
            <div class="sm:col-span-5 relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" 
                       name="search" 
                       value="{{ $filters['search'] ?? '' }}" 
                       placeholder="Cari task berdasarkan judul atau deskripsi..." 
                       class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 transition">
            </div>

            <!-- Status Filter -->
            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 transition">
                    <option value="">Semua Status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" {{ ($filters['status'] ?? null) === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div class="sm:col-span-2">
                <select name="sort" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 transition">
                    <option value="latest" {{ ($filters['sort'] ?? null) === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ ($filters['sort'] ?? null) === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="due_asc" {{ ($filters['sort'] ?? null) === 'due_asc' ? 'selected' : '' }}>Due Date (Terdekat)</option>
                    <option value="due_desc" {{ ($filters['sort'] ?? null) === 'due_desc' ? 'selected' : '' }}>Due Date (Terjauh)</option>
                    <option value="title_asc" {{ ($filters['sort'] ?? null) === 'title_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold py-2 px-3 rounded-xl transition shadow-xs text-center">
                    Terapkan
                </button>
                @if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['overdue']) || ($filters['sort'] ?? 'latest') !== 'latest')
                    <a href="{{ route('tasks.index') }}" title="Reset Filter" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Task List Table / Cards -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if ($tasks->isEmpty())
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Belum ada task yang ditemukan</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    @if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['overdue']))
                        Tidak ada task yang cocok dengan filter pencarian Anda. Coba ubah kata kunci atau reset filter.
                    @else
                        Mulai atur pekerjaan Anda sekarang dengan menambahkan task baru pertama Anda.
                    @endif
                </p>
                <div class="mt-5 flex items-center justify-center gap-3">
                    @if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['overdue']))
                        <a href="{{ route('tasks.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                            Reset Filter
                        </a>
                    @else
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Buat Task Pertama</span>
                        </a>
                    @endif
                </div>
            </div>
        @else
            <!-- Responsive Table View -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 sm:px-6">Status & Info Task</th>
                            <th class="py-3.5 px-4 hidden md:table-cell">Deskripsi</th>
                            <th class="py-3.5 px-4">Jatuh Tempo</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($tasks as $task)
                            <tr class="hover:bg-slate-50/70 transition group">
                                <!-- Status & Title -->
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="flex items-start gap-3">
                                        <!-- Quick Status Dropdown Form -->
                                        <form action="{{ route('tasks.status', $task) }}" method="POST" class="mt-0.5 shrink-0">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" 
                                                    onchange="this.form.submit()" 
                                                    title="Ubah Status Cepat"
                                                    class="text-xs font-semibold rounded-lg px-2.5 py-1 border transition cursor-pointer bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-300">
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->value }}" {{ $task->status === $status ? 'selected' : '' }}>
                                                        {{ $status->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>

                                        <div>
                                            <a href="{{ route('tasks.show', $task) }}" class="text-sm font-bold text-slate-900 hover:text-slate-600 transition block">
                                                {{ $task->title }}
                                            </a>
                                            <!-- Mobile Description snippet -->
                                            @if ($task->description)
                                                <p class="text-xs text-slate-500 mt-1 line-clamp-1 md:hidden">
                                                    {{ $task->description }}
                                                </p>
                                            @endif
                                            <span class="text-[11px] text-slate-400 block mt-1">
                                                Dibuat {{ $task->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Description (Desktop) -->
                                <td class="py-4 px-4 hidden md:table-cell text-xs text-slate-600 max-w-xs">
                                    @if ($task->description)
                                        <p class="line-clamp-2 leading-relaxed">
                                            {{ $task->description }}
                                        </p>
                                    @else
                                        <span class="text-slate-400 italic">Tidak ada deskripsi</span>
                                    @endif
                                </td>

                                <!-- Due Date with Overdue Indicator -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if ($task->due_date)
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs font-semibold text-slate-700">
                                                {{ $task->due_date->translatedFormat('d M Y') }}
                                            </span>
                                            @if ($task->isOverdue())
                                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-700 bg-slate-100 border border-slate-300 px-2 py-0.5 rounded-md w-fit">
                                                    <svg class="w-3 h-3 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5zm0 7.5a.75.75 0 10-1.5 0 .75.75 0 001.5 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Terlambat {{ $task->due_date->diffForHumans(['parts' => 1]) }}
                                                </span>
                                            @elseif ($task->isDueToday())
                                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-700 bg-slate-100 border border-slate-300 px-2 py-0.5 rounded-md w-fit">
                                                    Hari Ini
                                                </span>
                                            @else
                                                <span class="text-[11px] text-slate-400">
                                                    {{ $task->due_date->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">-</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1 sm:gap-2">
                                        <!-- View Detail -->
                                        <a href="{{ route('tasks.show', $task) }}" 
                                           title="Lihat Detail Task"
                                           class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('tasks.edit', $task) }}" 
                                           title="Edit Task"
                                           class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Delete with Confirmation -->
                                        <form action="{{ route('tasks.destroy', $task) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus task ini: &quot;{{ addslashes($task->title) }}&quot;?')" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Hapus Task"
                                                    class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if ($tasks->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                    {{ $tasks->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
