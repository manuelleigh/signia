@props(['id', 'label' => 'JSON'])

<div class="overflow-hidden rounded-xl border border-slate-800 bg-[#0b1220] shadow-sm">
    <div class="flex items-center justify-between border-b border-white/10 px-4 py-2.5">
        <span class="font-mono text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $label }}</span>
        <button type="button" data-copy="{{ $id }}" class="rounded-md px-2.5 py-1.5 text-xs font-semibold text-slate-400 transition hover:bg-white/10 hover:text-white" aria-label="Copiar código">
            <span class="copy-default">Copiar</span>
            <span class="copy-success text-emerald-300">Copiado</span>
        </button>
    </div>
    <pre class="overflow-x-auto p-5 text-[13px] leading-6 text-slate-200"><code id="{{ $id }}">{{ $slot }}</code></pre>
</div>
