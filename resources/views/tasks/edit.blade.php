@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium truncate">
        <a href="{{ route('tasks.index') }}" class="hover:text-slate-900 transition">Daftar Task</a>
        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('tasks.show', $task) }}" class="hover:text-slate-900 transition truncate max-w-[200px]">
            {{ $task->title }}
        </a>
        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-800 font-semibold shrink-0">Edit</span>
    </nav>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Edit Task</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Ubah rincian, status, atau tenggat waktu pekerjaan ini.</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg border {{ $task->status->badgeClasses() }}">
                    {{ $task->status->label() }}
                </span>
            </div>
        </div>

        <form action="{{ route('tasks.update', $task) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Judul Task <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $task->title) }}" 
                       required 
                       autofocus 
                       class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('title') border-rose-300 ring-2 ring-rose-100 bg-rose-50/20 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 transition">
                @error('title')
                    <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status & Due Date Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('status') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 transition">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" {{ old('status', $task->status->value) === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jatuh Tempo (Opsional)
                    </label>
                    <input type="date" 
                           id="due_date" 
                           name="due_date" 
                           value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('due_date') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 transition">
                    @error('due_date')
                        <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Deskripsi (Opsional)
                    </label>
                    <span class="text-[11px] text-slate-400">Boleh dikosongkan</span>
                </div>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          placeholder="Tambahkan detail atau instruksi tugas..."
                          class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('description') border-rose-300 ring-2 ring-rose-100 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 focus:border-slate-400 transition">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 font-semibold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('tasks.show', $task) }}" 
                   class="px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
