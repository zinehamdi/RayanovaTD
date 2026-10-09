<x-layouts.admin :title="__('admin.dashboard_title') . ' — ' . __('admin.brand_name')">

    <div class="space-y-8">
        
        <!-- Welcome Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs sm:text-sm uppercase tracking-widest text-gold-700 font-extrabold flex items-center gap-1.5">
                    <span>✨</span>
                    <span>{{ __('admin.dashboard_badge') }}</span>
                </span>
                <h1 class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1 tracking-tight">{{ __('admin.dashboard_title') }}</h1>
                <p class="text-sm sm:text-base text-slate-600 mt-1">{{ __('admin.dashboard_subtitle') }}</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Download PDF Catalogue -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" type="button"
                            class="px-5 py-3 rounded-2xl bg-white hover:bg-gold-50 border-2 border-gold-400/80 text-slate-950 font-bold text-sm md:text-base shadow-md transition-all flex items-center gap-2 cursor-pointer">
                        <span>📥</span>
                        <span>{{ __('admin.download_catalogue_pdf') }}</span>
                        <svg class="w-4 h-4 text-gold-600 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" x-transition style="display: none;"
                         class="absolute right-0 mt-2 w-60 rounded-2xl bg-white border-2 border-gold-400/80 shadow-2xl z-50 overflow-hidden py-2 divide-y divide-slate-100">
                        <a href="{{ route('admin.products.catalogue_pdf', ['lang' => 'en']) }}"
                           class="flex items-center justify-between px-4 py-2.5 text-sm font-bold text-slate-800 hover:bg-gold-50 hover:text-slate-950 transition-colors">
                            <span class="flex items-center gap-2"><span>🇬🇧</span> English Edition</span>
                            <span class="text-xs font-mono text-gold-700 bg-gold-100 px-2 py-0.5 rounded font-black">PDF</span>
                        </a>
                        <a href="{{ route('admin.products.catalogue_pdf', ['lang' => 'fr']) }}"
                           class="flex items-center justify-between px-4 py-2.5 text-sm font-bold text-slate-800 hover:bg-gold-50 hover:text-slate-950 transition-colors">
                            <span class="flex items-center gap-2"><span>🇫🇷</span> Édition Française</span>
                            <span class="text-xs font-mono text-gold-700 bg-gold-100 px-2 py-0.5 rounded font-black">PDF</span>
                        </a>
                        <a href="{{ route('admin.products.catalogue_pdf', ['lang' => 'ar']) }}"
                           class="flex items-center justify-between px-4 py-2.5 text-sm font-bold text-slate-800 hover:bg-gold-50 hover:text-slate-950 transition-colors">
                            <span class="flex items-center gap-2"><span>🇸🇦</span> النسخة العربية</span>
                            <span class="text-xs font-mono text-gold-700 bg-gold-100 px-2 py-0.5 rounded font-black">PDF</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.quotations.index') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-300 text-slate-950 text-sm md:text-base font-black shadow-lg shadow-gold-500/25 ring-2 ring-gold-300 transition-all flex items-center gap-2 cursor-pointer">
                    <span>{{ __('admin.manage_pipeline') }}</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.company_hero.edit') }}#background-image-section" class="px-5 py-3 rounded-2xl bg-white hover:bg-gold-50 border-2 border-gold-400/80 text-slate-900 text-sm md:text-base font-bold shadow-md transition-all flex items-center gap-2 cursor-pointer">
                    <span>🖼️</span>
                    <span>{{ __('admin.change_background_image') }}</span>
                </a>

                <a href="{{ route('admin.slides.create') }}" class="px-5 py-3 rounded-2xl bg-white hover:bg-amber-50/50 border-2 border-slate-200 text-slate-800 text-sm md:text-base font-bold shadow-md transition-all">
                    {{ __('admin.add_slide') }}
                </a>
            </div>
        </div>

        <!-- 4 Key Performance Indicators (KPIs) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Total Pipeline Value -->
            <div class="bg-white rounded-3xl p-6 border-2 border-gold-400/40 shadow-xl shadow-gold-500/10 space-y-3 hover:shadow-2xl transition-all">
                <div class="flex items-center justify-between text-sm font-bold text-slate-600">
                    <span>{{ __('admin.priced_pipeline') }}</span>
                    <span class="text-2xl p-2 rounded-xl bg-amber-100/80">💰</span>
                </div>
                <div class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900">
                    €{{ number_format($totalPipelineValue, 2) }}
                </div>
                <div class="text-xs sm:text-sm text-emerald-700 flex items-center gap-1.5 font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>{{ __('admin.active_trade_value') }}</span>
                </div>
            </div>

            <!-- Total RFQs Received -->
            <div class="bg-white rounded-3xl p-6 border-2 border-gold-400/40 shadow-xl shadow-gold-500/10 space-y-3 hover:shadow-2xl transition-all">
                <div class="flex items-center justify-between text-sm font-bold text-slate-600">
                    <span>{{ __('admin.total_rfq') }}</span>
                    <span class="text-2xl p-2 rounded-xl bg-blue-100/80">📥</span>
                </div>
                <div class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900">
                    {{ $totalQuotations }}
                </div>
                <div class="text-xs sm:text-sm text-slate-600 flex items-center gap-1.5 font-semibold">
                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 font-bold border border-amber-300">
                        {{ $newInquiries }} {{ __('admin.pending_review') }}
                    </span>
                </div>
            </div>

            <!-- Confirmed Deals -->
            <div class="bg-white rounded-3xl p-6 border-2 border-gold-400/40 shadow-xl shadow-gold-500/10 space-y-3 hover:shadow-2xl transition-all">
                <div class="flex items-center justify-between text-sm font-bold text-slate-600">
                    <span>{{ __('admin.confirmed_deals') }}</span>
                    <span class="text-2xl p-2 rounded-xl bg-emerald-100/80">🤝</span>
                </div>
                <div class="font-serif text-3xl sm:text-4xl font-extrabold text-emerald-700">
                    {{ $confirmedDeals }}
                </div>
                <div class="text-xs sm:text-sm text-emerald-800 flex items-center gap-1.5 font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>{{ __('admin.approved_magic_link') }}</span>
                </div>
            </div>

            <!-- Export Contracts Generated -->
            <div class="bg-white rounded-3xl p-6 border-2 border-gold-400/40 shadow-xl shadow-gold-500/10 space-y-3 hover:shadow-2xl transition-all">
                <div class="flex items-center justify-between text-sm font-bold text-slate-600">
                    <span>{{ __('admin.contracts_generated') }}</span>
                    <span class="text-2xl p-2 rounded-xl bg-purple-100/80">📄</span>
                </div>
                <div class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900">
                    {{ $contractsCount }}
                </div>
                <div class="text-xs sm:text-sm text-purple-700 flex items-center gap-1.5 font-bold">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    <span>{{ __('admin.incoterms_compliant') }}</span>
                </div>
            </div>

        </div>

        <!-- Main Grid: Recent Inquiries + Top Markets -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Recent Quotations Table -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border-2 border-gold-400/30 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 uppercase tracking-wide">{{ __('admin.latest_inquiries') }}</h2>
                        <p class="text-sm text-slate-600">{{ __('admin.inquiries_subtitle') }}</p>
                    </div>
                    <a href="{{ route('admin.quotations.index') }}" class="text-sm text-gold-700 hover:text-gold-900 font-extrabold flex items-center gap-1">
                        <span>{{ __('admin.view_all_deals') }}</span>
                        <span>→</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left rtl:text-right text-sm md:text-base">
                        <thead>
                            <tr class="border-b-2 border-slate-200 bg-amber-50/50 text-slate-700 uppercase text-xs font-bold tracking-wider">
                                <th class="py-4 px-4 rounded-l-xl rtl:rounded-l-none rtl:rounded-r-xl">{{ __('admin.reference') }}</th>
                                <th class="py-4 px-4">{{ __('admin.company_country') }}</th>
                                <th class="py-4 px-4">{{ __('admin.terms') }}</th>
                                <th class="py-4 px-4">{{ __('admin.status') }}</th>
                                <th class="py-4 px-4 text-right rtl:text-left rounded-r-xl rtl:rounded-r-none rtl:rounded-l-xl">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentQuotations as $quote)
                                <tr class="hover:bg-amber-50/30 transition-all">
                                    <td class="py-4 px-4 font-mono text-gold-700 font-extrabold text-sm md:text-base">
                                        {{ $quote->reference_number }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="font-extrabold text-slate-900 text-sm md:text-base">{{ $quote->company_name }}</div>
                                        <div class="text-xs md:text-sm text-slate-500 font-medium">{{ $quote->country }} • {{ $quote->destination_port }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="px-3 py-1 rounded-xl bg-amber-100 text-xs font-black text-amber-950 border border-amber-300 shadow-sm">
                                            {{ $quote->incoterm }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        @php
                                            $statusColors = [
                                                'new' => 'bg-amber-100 text-amber-950 border-amber-400',
                                                'quoted' => 'bg-blue-100 text-blue-950 border-blue-400',
                                                'client_viewed' => 'bg-cyan-100 text-cyan-950 border-cyan-400',
                                                'client_modified' => 'bg-purple-100 text-purple-950 border-purple-400',
                                                'confirmed' => 'bg-emerald-100 text-emerald-950 border-emerald-400',
                                                'contract_issued' => 'bg-indigo-100 text-indigo-950 border-indigo-400',
                                                'closed' => 'bg-slate-200 text-slate-900 border-slate-300',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-full text-xs font-black border {{ $statusColors[$quote->status] ?? 'bg-slate-100 text-slate-800' }} shadow-sm">
                                            {{ __('admin.status_' . $quote->status) ?? ucfirst(str_replace('_', ' ', $quote->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right rtl:text-left">
                                        <a href="{{ route('admin.quotations.show', $quote->id) }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-300 text-slate-950 font-black text-xs md:text-sm shadow-md transition-all inline-block">
                                            {{ __('admin.inspect') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500 font-medium">
                                        {{ __('admin.no_inquiries') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right 1 Col: Top Importing Markets & Protocols -->
            <div class="space-y-6">
                
                <!-- Destination Countries Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-gold-400/30 shadow-2xl space-y-5">
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 uppercase tracking-wide flex items-center justify-between">
                        <span>{{ __('admin.top_markets') }}</span>
                        <span class="text-2xl">🌍</span>
                    </h3>

                    <div class="space-y-3">
                        @forelse($topCountries as $dest)
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-amber-50/40 border border-gold-300/40">
                                <span class="font-bold text-slate-800 text-sm md:text-base">{{ $dest->country }}</span>
                                <span class="px-3 py-1 rounded-full bg-gold-500 text-slate-950 font-mono font-black text-xs md:text-sm shadow-sm">
                                    {{ $dest->count }} {{ __('admin.deals_count') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 font-medium">{{ __('admin.awaiting_submissions') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Export Compliance & Security Notice (Deep Imperial Emerald) -->
                <div class="bg-gradient-to-br from-[#0C2B1E] via-[#0E3324] to-[#081C13] rounded-3xl p-6 sm:p-8 border-2 border-gold-400/40 text-white shadow-2xl space-y-3.5">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🛡️</span>
                        <span class="text-sm font-extrabold uppercase tracking-wider text-gold-300">{{ __('admin.security_protocol') }}</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-medium">
                        {{ __('admin.security_protocol_text') }}
                    </p>
                </div>

            </div>

        </div>

    </div>

</x-layouts.admin>
