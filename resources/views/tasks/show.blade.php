@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium truncate">
        <a href="{{ route('tasks.index') }}" class="hover:text-slate-900 transition">Daftar Task</a>
        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-800 font-semibold truncate">{{ $task->title }}</span>
    </nav>

    @if ($task->isOverdue())
        <div class="p-4 rounded-2xl bg-slate-100 border border-slate-300 text-slate-800 flex items-start gap-3">
            <svg class="w-5 h-5 text-slate-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h4 class="text-sm font-bold text-slate-900">Task Ini Sudah Melewati Jatuh Tempo!</h4>
                <p class="text-xs text-slate-600 mt-0.5">
                    Tenggat waktu pengerjaan berakhir pada <strong>{{ $task->due_date->translatedFormat('d F Y') }}</strong> ({{ $task->due_date->diffForHumans() }}). Segera selesaikan dan ubah status ke Completed.
                </p>
            </div>
        </div>
    @elseif ($task->isDueToday())
        <div class="p-4 rounded-2xl bg-slate-100 border border-slate-300 text-slate-800 flex items-start gap-3">
            <svg class="w-5 h-5 text-slate-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h4 class="text-sm font-bold text-slate-900">Jatuh Tempo Hari Ini!</h4>
                <p class="text-xs text-slate-600 mt-0.5">
                    Task ini dijadwalkan untuk diselesaikan pada hari ini.
                </p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        
        <div class="p-6 sm:p-8 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-mono font-bold text-slate-400">#{{ $task->id }}</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $task->status->badgeClasses() }}">
                            <span class="w-2 h-2 rounded-full {{ $task->status->dotClass() }}"></span>
                            {{ $task->status->label() }}
                        </span>
                        @if ($task->isOverdue())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-300">
                                Overdue
                            </span>
                        @endif
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">
                        {{ $task->title }}
                    </h1>
                </div>

                <form action="{{ route('tasks.status', $task) }}" method="POST" class="shrink-0 flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <label for="quick_status" class="text-xs text-slate-500 font-medium">Ubah Status:</label>
                    <select id="quick_status"
                            name="status"
                            onchange="this.form.submit()"
                            class="text-xs font-semibold rounded-xl px-3 py-2 border border-slate-200 bg-slate-50 hover:bg-white focus:outline-none focus:ring-2 focus:ring-slate-400 transition cursor-pointer">
                        @foreach (\App\Enums\TaskStatus::cases() as $statusOption)
                            <option value="{{ $statusOption->value }}" {{ $task->status === $statusOption ? 'selected' : '' }}>
                                {{ $statusOption->label() }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-3">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Task</h3>
            <div class="text-sm text-slate-700 leading-relaxed bg-slate-50/70 p-5 rounded-xl border border-slate-100 min-h-[100px]">
                @if ($task->description)
                    <div class="whitespace-pre-line">{!! nl2br(e($task->description)) !!}</div>
                @else
                    <span class="text-slate-400 italic">Tidak ada deskripsi untuk task ini.</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 border-t border-slate-100 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 bg-slate-50/40 text-xs">
            
            <div class="p-5">
                <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[10px]">Tenggat Waktu</span>
                <div class="mt-1">
                    @if ($task->due_date)
                        <span class="font-bold text-slate-900 text-sm block">
                            {{ $task->due_date->translatedFormat('l, d M Y') }}
                        </span>
                        <span class="text-slate-500 text-[11px] block mt-0.5">
                            {{ $task->due_date->diffForHumans() }}
                        </span>
                    @else
                        <span class="text-slate-400 italic">Tidak ada batas waktu</span>
                    @endif
                </div>
            </div>

            <div class="p-5">
                <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[10px]">Waktu Dibuat</span>
                <div class="mt-1">
                    <span class="font-bold text-slate-900 text-sm block">
                        {{ $task->created_at->translatedFormat('d M Y, H:i') }}
                    </span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">
                        {{ $task->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>

            <div class="p-5">
                <span class="text-slate-400 font-semibold block uppercase tracking-wider text-[10px]">Terakhir Diperbarui</span>
                <div class="mt-1">
                    <span class="font-bold text-slate-900 text-sm block">
                        {{ $task->updated_at->translatedFormat('d M Y, H:i') }}
                    </span>
                    <span class="text-slate-500 text-[11px] block mt-0.5">
                        {{ $task->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-slate-100 bg-white flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="{{ route('tasks.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Daftar</span>
            </a>

            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                
                <a href="{{ route('tasks.edit', $task) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Task</span>
                </a>

                <form action="{{ route('tasks.destroy', $task) }}"
                      method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus task ini secara permanen?')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
