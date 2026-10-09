<x-layouts.admin :title="__('admin.quotations_pipeline_title') . ' — ' . __('admin.brand_name')">

    <div class="space-y-8">
        
        <!-- Header & Search -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <span class="text-xs sm:text-sm uppercase tracking-widest text-gold-700 font-extrabold flex items-center gap-1.5">
                    <span>📜</span>
                    <span>{{ __('admin.command_center') }}</span>
                </span>
                <h1 class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1">{{ __('admin.quotations_pipeline_title') }}</h1>
                <p class="text-sm sm:text-base text-slate-600 mt-1">{{ __('admin.quotations_pipeline_subtitle') }}</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.quotations.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                @if($statusFilter)
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('admin.search_placeholder') }}"
                       class="px-4 py-3 rounded-2xl bg-white border-2 border-slate-200 text-slate-900 placeholder-slate-400 text-sm sm:text-base w-72 focus:border-gold-500 focus:ring-4 focus:ring-gold-400/20 focus:outline-none shadow-sm font-medium">
                <button type="submit" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-300 text-slate-950 text-sm sm:text-base font-black shadow-lg shadow-gold-500/20 ring-1 ring-gold-300 transition-all cursor-pointer">
                    {{ __('admin.search') }}
                </button>
                @if($search)
                    <a href="{{ route('admin.quotations.index', ['status' => $statusFilter]) }}" class="text-sm font-bold text-slate-600 hover:text-slate-900 px-2 underline">
                        {{ __('admin.clear') }}
                    </a>
                @endif
            </form>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-none">
            @php
                $tabs = [
                    'all' => __('admin.tab_all'),
                    'new' => __('admin.tab_new'),
                    'quoted' => __('admin.tab_quoted'),
                    'client_viewed' => __('admin.tab_client_viewed'),
                    'client_modified' => __('admin.tab_client_modified'),
                    'confirmed' => __('admin.tab_confirmed'),
                    'contract_issued' => __('admin.tab_contract_issued'),
                    'closed' => __('admin.tab_closed'),
                ];
            @endphp

            @foreach($tabs as $key => $label)
                @php
                    $isActive = ($statusFilter === $key) || (!$statusFilter && $key === 'all');
                @endphp
                <a href="{{ route('admin.quotations.index', ['status' => $key, 'q' => $search]) }}"
                   class="px-4 py-3 rounded-2xl text-sm md:text-base font-bold shrink-0 transition-all flex items-center gap-2.5 {{ $isActive ? 'bg-gradient-to-r from-gold-500 to-gold-400 text-slate-950 font-black shadow-lg shadow-gold-500/25 ring-2 ring-gold-300' : 'bg-white hover:bg-amber-50/60 text-slate-700 border-2 border-slate-200 shadow-sm' }}">
                    <span>{{ $label }}</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black {{ $isActive ? 'bg-slate-950 text-gold-300' : 'bg-amber-100 text-amber-950 border border-amber-300' }}">
                        {{ $counts[$key] ?? 0 }}
                    </span>
                </a>
            @endforeach
        </div>

        <!-- Main Quotations Table -->
        <div class="bg-white rounded-3xl border-2 border-gold-400/30 overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left rtl:text-right text-sm md:text-base">
                    <thead>
                        <tr class="border-b-2 border-slate-200 bg-amber-50/60 text-slate-700 uppercase text-xs font-bold tracking-wider">
                            <th class="py-4 px-6">{{ __('admin.reference') }}</th>
                            <th class="py-4 px-6">{{ __('admin.buyer_contact') }}</th>
                            <th class="py-4 px-6">{{ __('admin.destination_port') }}</th>
                            <th class="py-4 px-6">{{ __('admin.terms') }}</th>
                            <th class="py-4 px-6">{{ __('admin.total_price') }}</th>
                            <th class="py-4 px-6">{{ __('admin.status') }}</th>
                            <th class="py-4 px-6 text-right rtl:text-left">{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($quotations as $quote)
                            <tr class="hover:bg-amber-50/30 transition-all">
                                <td class="py-5 px-6 font-mono text-gold-700 font-black text-sm sm:text-base">
                                    <a href="{{ route('admin.quotations.show', $quote->id) }}" class="hover:underline">
                                        {{ $quote->reference_number }}
                                    </a>
                                    <div class="text-xs text-slate-500 font-sans font-medium mt-0.5">
                                        {{ $quote->created_at->format('Y-m-d H:i') }}
                                    </div>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="font-extrabold text-slate-900 text-base">{{ $quote->company_name }}</div>
                                    <div class="text-slate-600 text-xs sm:text-sm font-medium mt-0.5">{{ $quote->contact_name }} • {{ $quote->email }}</div>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="font-bold text-slate-900">{{ $quote->country }}</div>
                                    <div class="text-xs sm:text-sm text-slate-500 font-medium">{{ $quote->destination_port }}</div>
                                </td>
                                <td class="py-5 px-6">
                                    <span class="px-3 py-1.5 rounded-xl bg-amber-100 border border-amber-300 font-black text-xs sm:text-sm text-amber-950 shadow-sm">
                                        {{ $quote->incoterm }}
                                    </span>
                                </td>
                                <td class="py-5 px-6">
                                    @if($quote->total_amount)
                                        <span class="font-black text-emerald-700 font-mono text-base sm:text-lg">
                                            {{ number_format($quote->total_amount, 2) }} {{ $quote->currency }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic font-medium text-sm">{{ __('admin.unquoted') }}</span>
                                    @endif
                                </td>
                                <td class="py-5 px-6">
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
                                    <span class="px-3.5 py-1.5 rounded-full text-xs font-black border {{ $statusColors[$quote->status] ?? 'bg-slate-100 text-slate-800' }} shadow-sm">
                                        {{ __('admin.status_' . $quote->status) ?? ucfirst(str_replace('_', ' ', $quote->status)) }}
                                    </span>
                                </td>
                                <td class="py-5 px-6 text-right rtl:text-left space-x-2 rtl:space-x-reverse">
                                    <a href="{{ route('admin.quotations.show', $quote->id) }}"
                                       class="inline-block px-4 py-2.5 rounded-xl bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-300 text-slate-950 font-black text-xs sm:text-sm shadow-md transition-all">
                                        {{ __('admin.review_quote') }}
                                    </a>

                                    <!-- Deletion strictly reserved for Super Admin -->
                                    @can('delete_quotations')
                                        <form action="{{ route('admin.quotations.destroy', $quote->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('CRITICAL: As Super Admin, do you confirm permanent deletion of deal {{ $quote->reference_number }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3.5 py-2 rounded-xl bg-red-100 hover:bg-red-200 border border-red-300 text-red-800 text-xs sm:text-sm font-bold transition-all cursor-pointer">
                                                {{ __('admin.delete') }}
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-500 font-medium text-base">
                                    {{ __('admin.no_inquiries') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($quotations->hasPages())
                <div class="p-6 border-t border-slate-100 bg-amber-50/30">
                    {{ $quotations->links() }}
                </div>
            @endif
        </div>

    </div>

</x-layouts.admin>
