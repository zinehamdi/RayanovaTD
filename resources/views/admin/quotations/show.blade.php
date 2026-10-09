<x-layouts.admin :title="'Quotation Deal ' . $quotation->reference_number . ' — ' . __('admin.brand_name')">

    <div class="space-y-8">
        
        <!-- Header & Quick Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 border-b-2 border-slate-200 pb-6">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.quotations.index') }}" class="text-sm font-extrabold text-gold-700 hover:text-gold-900 flex items-center gap-1">
                        <span>{{ __('admin.back_to_pipeline') }}</span>
                    </a>
                    <span class="text-slate-400">•</span>
                    <span class="font-mono text-xs sm:text-sm text-slate-500 font-medium">{{ __('admin.date') }}: {{ $quotation->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-3.5 mt-2">
                    <h1 class="font-serif text-3xl sm:text-4xl font-black text-slate-900 font-mono tracking-tight">
                        {{ $quotation->reference_number }}
                    </h1>
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
                    <span class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-black border {{ $statusColors[$quotation->status] ?? 'bg-slate-100 text-slate-800' }} shadow-sm">
                        {{ __('admin.status_' . $quotation->status) ?? ucfirst(str_replace('_', ' ', $quotation->status)) }}
                    </span>
                </div>
            </div>

            <!-- Magic Link Copy & Contract Trigger -->
            <div class="flex flex-wrap items-center gap-3">
                @php
                    $magicUrl = route('quote.review', ['locale' => app()->getLocale(), 'reference' => $quotation->reference_number]) . '?token=' . $quotation->signed_token;
                @endphp
                <button type="button" onclick="navigator.clipboard.writeText('{{ $magicUrl }}'); alert('Client Magic Link copied to clipboard!');"
                        class="px-4 py-3 rounded-2xl bg-white hover:bg-amber-50/60 border-2 border-slate-200 text-xs sm:text-sm font-bold text-slate-800 shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                    <span class="text-base">🔗</span>
                    <span>{{ __('admin.copy_magic_link') }}</span>
                </button>

                @if($quotation->status === 'confirmed' && !$quotation->contract)
                    <a href="{{ route('admin.contracts.create', $quotation->id) }}"
                       class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-black text-xs sm:text-sm uppercase tracking-wider shadow-lg shadow-emerald-600/25 transition-all flex items-center gap-2">
                        <span>{{ __('admin.build_contract') }}</span>
                    </a>
                @endif

                @if($quotation->contract)
                    <a href="{{ route('admin.contracts.download', $quotation->contract->id) }}"
                       class="px-4 py-3 rounded-2xl bg-purple-100 hover:bg-purple-200 border-2 border-purple-300 text-purple-900 text-xs sm:text-sm font-black transition-all flex items-center gap-2 shadow-sm">
                        <span>{{ __('admin.download_contract_pdf') }}</span>
                    </a>

                    @if($quotation->contract->invoices->first())
                        <a href="{{ route('admin.invoices.download', $quotation->contract->invoices->first()->id) }}"
                           class="px-4 py-3 rounded-2xl bg-blue-100 hover:bg-blue-200 border-2 border-blue-300 text-blue-900 text-xs sm:text-sm font-black transition-all flex items-center gap-2 shadow-sm">
                            <span>{{ __('admin.download_invoice_pdf') }}</span>
                        </a>
                    @endif
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Line Items & Pricing Desk -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Pricing Desk Form -->
                <form action="{{ route('admin.quotations.price', $quotation->id) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-gold-400/30 shadow-2xl space-y-6">
                    @csrf

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-5">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 uppercase tracking-wide flex items-center gap-2">
                                <span>{{ __('admin.pricing_desk') }}</span>
                                <span class="px-2.5 py-0.5 rounded-lg bg-amber-100 text-amber-950 text-xs font-black border border-amber-300">Incoterms 2020</span>
                            </h2>
                            <p class="text-sm text-slate-600 mt-1">{{ __('admin.pricing_desk_subtitle') }}</p>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <label class="text-sm font-bold text-slate-800">{{ __('admin.currency') }}:</label>
                            <select name="currency" class="px-4 py-2.5 rounded-2xl bg-amber-50/50 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-black focus:border-gold-500 focus:outline-none shadow-sm">
                                <option value="EUR" {{ ($quotation->currency ?? 'EUR') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                <option value="USD" {{ ($quotation->currency ?? '') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="GBP" {{ ($quotation->currency ?? '') === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                <option value="TND" {{ ($quotation->currency ?? '') === 'TND' ? 'selected' : '' }}>TND (DT)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Line Items Table -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">
                                {{ __('admin.order_items_specs') }}
                            </label>
                            <span class="text-xs text-slate-500 font-bold">
                                {{ count($quotation->items) }} {{ count($quotation->items) > 1 ? 'items' : 'item' }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            @foreach($quotation->items as $item)
                                @php
                                    $product = $item->product;
                                    $category = $product?->category;
                                    $prodTitle = $product ? $product->localized_title : ($item->custom_branding_notes ?? 'Export Commodity');
                                    $isAr = app()->getLocale() === 'ar';
                                    $secondaryTitle = $product ? ($isAr ? ($product->title['en'] ?? '') : ($product->title['ar'] ?? '')) : '';
                                    $categoryName = $category ? $category->localized_name : '';
                                    $imageUrl = $product ? $product->image_url : asset('images/products/olive_oil_bottle.jpg');
                                @endphp

                                <div x-data="{ unitPrice: {{ (float)($item->unit_price ?? old('items.'.$item->id.'.unit_price', 0)) }}, qty: {{ (float)$item->quantity }} }"
                                     class="p-5 sm:p-6 rounded-3xl bg-gradient-to-br from-amber-50/60 via-white to-amber-50/30 border-2 border-gold-300/80 shadow-md hover:shadow-lg transition-all space-y-4">
                                    
                                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-5">
                                        <!-- Left: Product Image & Details -->
                                        <div class="flex items-start gap-4 flex-1">
                                            <!-- Thumbnail -->
                                            <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white border-2 border-gold-200/80 p-1.5 shadow-sm shrink-0 flex items-center justify-center overflow-hidden">
                                                <img src="{{ $imageUrl }}" alt="{{ $prodTitle }}" class="w-full h-full object-contain rounded-xl">
                                                @if($categoryName)
                                                    <span class="absolute -top-1 -right-1 px-2 py-0.5 rounded-md bg-emerald-700 text-white text-[9px] font-black uppercase tracking-wider shadow-xs">
                                                        {{ $categoryName }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Titles & Badges -->
                                            <div class="space-y-2 flex-1 min-w-0">
                                                <div>
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <h3 class="text-base sm:text-lg font-black text-slate-900 leading-tight">
                                                            {{ $prodTitle }}
                                                        </h3>
                                                        @if($product && $product->slug)
                                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-500 font-mono text-[10px] font-bold">
                                                                {{ $product->slug }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if($secondaryTitle)
                                                        <div class="text-xs text-gold-800 font-bold font-mono mt-0.5">
                                                            {{ $secondaryTitle }}
                                                        </div>
                                                    @endif

                                                    @if($product && $product->localized_subtitle)
                                                        <p class="text-xs text-slate-600 font-medium mt-1 line-clamp-1">
                                                            {{ $product->localized_subtitle }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <!-- Packaging & Attributes Badges -->
                                                <div class="flex flex-wrap items-center gap-2 pt-1">
                                                    <span class="px-3 py-1 rounded-xl bg-white border-2 border-amber-200/80 font-black text-xs text-slate-800 shadow-xs flex items-center gap-1.5">
                                                        <span class="text-gold-600">📦</span>
                                                        <span>{{ __('admin.packaging') }}</span>
                                                        <span class="text-gold-900">{{ $item->localized_packaging }}</span>
                                                    </span>

                                                    <span class="px-3 py-1 rounded-xl bg-white border-2 border-slate-200 font-black text-xs text-slate-800 shadow-xs flex items-center gap-1.5">
                                                        <span class="text-slate-500">📏</span>
                                                        <span>{{ __('admin.size') }}</span>
                                                        <span class="text-slate-950 font-mono">{{ $item->size }}</span>
                                                    </span>

                                                    <span class="px-3 py-1 rounded-xl bg-white border-2 border-emerald-200 font-black text-xs text-emerald-950 shadow-xs flex items-center gap-1.5">
                                                        <span>⚖️</span>
                                                        <span>{{ __('admin.quantity') }}</span>
                                                        <span class="font-mono text-sm text-emerald-800">{{ number_format($item->quantity) }}</span>
                                                        <span>{{ $item->unit }}</span>
                                                    </span>

                                                    @if($item->is_private_label)
                                                        <span class="px-3 py-1 rounded-xl bg-gradient-to-r from-gold-500 to-amber-400 text-slate-950 font-black text-xs shadow-xs flex items-center gap-1">
                                                            <span>🏷️</span>
                                                            <span>{{ __('admin.private_label_badge') }}</span>
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($item->custom_branding_notes)
                                                    <div class="p-2.5 rounded-xl bg-gold-50/80 border border-gold-300 text-xs text-gold-950 font-medium">
                                                        <span class="font-bold">ملاحظات التخصيص للمشتري:</span> {{ $item->custom_branding_notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Right: Pricing Input & Dynamic Line Total -->
                                        <div class="w-full sm:w-64 shrink-0 p-4 rounded-2xl bg-white border-2 border-gold-300/80 shadow-sm space-y-3">
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">
                                                    {{ __('admin.unit_price') }}
                                                </label>
                                                <div class="flex items-center rounded-xl bg-amber-50/50 border-2 border-slate-300 focus-within:border-gold-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-gold-400/20 overflow-hidden shadow-inner transition-all">
                                                    <input type="number" step="0.01" min="0.01"
                                                           name="items[{{ $item->id }}][unit_price]"
                                                           x-model.number="unitPrice"
                                                           value="{{ old('items.' . $item->id . '.unit_price', $item->unit_price ?? '') }}"
                                                           placeholder="0.00" required
                                                           class="w-full px-3.5 py-2.5 bg-transparent border-0 text-slate-900 font-mono font-black text-base focus:outline-none focus:ring-0">
                                                    <span class="px-3 py-2.5 bg-slate-100 border-l border-slate-200 rtl:border-l-0 rtl:border-r text-xs font-black text-slate-600 shrink-0 select-none">
                                                        / {{ $item->unit }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Dynamic Live Subtotal -->
                                            <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
                                                <span class="text-slate-500 font-bold">{{ __('admin.item_subtotal') }}</span>
                                                <span class="font-mono font-black text-sm text-emerald-700"
                                                      x-text="((unitPrice || 0) * qty).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ' + '{{ $quotation->currency ?? 'EUR' }}'">
                                                    {{ number_format(($item->unit_price ?? 0) * $item->quantity, 2) }} {{ $quotation->currency ?? 'EUR' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Additional Shipping / Insurance / Validity -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div class="space-y-2">
                            <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.ocean_freight') }}</label>
                            <input type="number" step="0.01" min="0" name="shipping_cost" value="0.00"
                                   class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.insurance_cost') }}</label>
                            <input type="number" step="0.01" min="0" name="insurance_cost" value="0.00"
                                   class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.validity_expiration') }}</label>
                            <input type="date" name="valid_until" required
                                   value="{{ old('valid_until', $quotation->valid_until ? $quotation->valid_until->format('Y-m-d') : now()->addDays(14)->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                        </div>
                    </div>

                    <!-- Admin Desk Internal Notes -->
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.pricing_notes') }}</label>
                        <textarea name="admin_notes" rows="2" placeholder="{{ __('admin.pricing_notes_placeholder') }}"
                                  class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 placeholder-slate-400 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('admin_notes', $quotation->admin_notes) }}</textarea>
                    </div>

                    <!-- Pricing Action Submit -->
                    <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            @if($quotation->total_amount)
                                <span class="text-sm font-bold text-slate-600">{{ __('admin.current_total') }}</span>
                                <span class="font-mono text-xl sm:text-2xl font-black text-emerald-700 ml-2 rtl:mr-2">
                                    {{ number_format($quotation->total_amount, 2) }} {{ $quotation->currency }}
                                </span>
                            @endif
                        </div>

                        <button type="submit" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-gold-500 via-gold-400 to-gold-500 hover:from-gold-400 hover:to-gold-300 text-slate-950 font-black text-sm sm:text-base uppercase tracking-wider shadow-xl shadow-gold-500/25 ring-2 ring-gold-300 transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <span>{{ __('admin.issue_quote_btn') }}</span>
                        </button>
                    </div>
                </form>

                <!-- Negotiation & Communication Thread -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-gold-400/30 shadow-2xl space-y-5">
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900 uppercase tracking-wide flex items-center gap-2">
                        <span>{{ __('admin.communication_history') }}</span>
                        <span>💬</span>
                    </h2>

                    <div class="space-y-3.5 max-h-80 overflow-y-auto pr-2 rtl:pl-2">
                        @forelse($quotation->messages as $msg)
                            <div class="p-4 rounded-2xl {{ $msg->sender_type === 'admin' ? 'bg-amber-50/80 border-2 border-gold-300 mr-6 rtl:mr-0 rtl:ml-6' : 'bg-slate-50 border-2 border-slate-200 ml-6 rtl:ml-0 rtl:mr-6' }} space-y-1.5">
                                <div class="flex items-center justify-between text-xs sm:text-sm">
                                    <span class="font-extrabold {{ $msg->sender_type === 'admin' ? 'text-gold-800' : 'text-slate-800' }}">
                                        {{ $msg->sender_name }} ({{ ucfirst($msg->sender_type) }})
                                    </span>
                                    <span class="text-slate-500 text-xs font-medium">
                                        {{ $msg->created_at->format('M d, Y H:i') }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-700 leading-relaxed font-medium">
                                    {{ $msg->message_body }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 text-center py-6 font-medium">{{ __('admin.no_messages') }}</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right 1 Col: Buyer Profile & Shipping Details -->
            <div class="space-y-6">
                
                <!-- Buyer Profile Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-gold-400/30 shadow-2xl space-y-4">
                    <h3 class="text-sm font-extrabold text-gold-700 uppercase tracking-wider">{{ __('admin.buyer_profile') }}</h3>

                    <div class="space-y-3.5 text-sm">
                        <div>
                            <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.company_name') }}</span>
                            <span class="font-extrabold text-slate-900 text-base sm:text-lg">{{ $quotation->company_name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.contact_officer') }}</span>
                            <span class="font-bold text-slate-800">{{ $quotation->contact_name }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.email_address') }}</span>
                            <a href="mailto:{{ $quotation->email }}" class="text-gold-700 font-bold hover:underline">{{ $quotation->email }}</a>
                        </div>
                        @if($quotation->phone)
                            <div>
                                <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.direct_phone') }}</span>
                                <span class="text-slate-800 font-mono font-bold">{{ $quotation->phone }}</span>
                            </div>
                        @endif
                        @if($quotation->whatsapp)
                            <div>
                                <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.whatsapp_desk') }}</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $quotation->whatsapp) }}" target="_blank" class="text-emerald-700 font-mono font-bold hover:underline flex items-center gap-1">
                                    <span>{{ $quotation->whatsapp }}</span>
                                    <span>↗</span>
                                </a>
                            </div>
                        @endif
                        @if($quotation->tax_number)
                            <div>
                                <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.tax_vat') }}</span>
                                <span class="text-slate-800 font-mono font-bold">{{ $quotation->tax_number }}</span>
                            </div>
                        @endif
                        @if($quotation->website)
                            <div>
                                <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.website') }}</span>
                                <a href="{{ $quotation->website }}" target="_blank" class="text-blue-600 font-bold hover:underline truncate block">
                                    {{ $quotation->website }} ↗
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Shipping & Incoterms Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-gold-400/30 shadow-2xl space-y-4">
                    <h3 class="text-sm font-extrabold text-gold-700 uppercase tracking-wider">{{ __('admin.logistics_terms') }}</h3>

                    <div class="space-y-3.5 text-sm">
                        <div>
                            <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.destination_country_port') }}</span>
                            <span class="font-extrabold text-slate-900 text-base">{{ $quotation->country }}</span>
                            <span class="block text-slate-600 font-medium">{{ $quotation->destination_port }}</span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.requested_incoterm') }}</span>
                            <span class="inline-block px-3 py-1 rounded-xl bg-amber-100 text-amber-950 font-black font-mono border border-amber-300 mt-1">
                                {{ $quotation->incoterm }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.shipping_method') }}</span>
                            <span class="text-slate-800 font-bold">
                                {{ ucfirst(str_replace('_', ' ', $quotation->shipping_method)) }}
                            </span>
                        </div>
                        @if($quotation->client_notes)
                            <div class="pt-3 border-t border-slate-100">
                                <span class="block text-xs uppercase font-bold text-slate-500 tracking-wider">{{ __('admin.client_notes') }}</span>
                                <p class="text-slate-700 italic text-sm mt-1.5 bg-amber-50/50 p-3.5 rounded-2xl border border-gold-200">
                                    "{{ $quotation->client_notes }}"
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Super Admin Deletion Guard -->
                @can('delete_quotations')
                    <div class="p-6 rounded-3xl bg-red-50 border-2 border-red-300 shadow-xl space-y-3">
                        <span class="text-xs sm:text-sm font-black text-red-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span>⚠️</span>
                            <span>{{ __('admin.super_admin_zone') }}</span>
                        </span>
                        <p class="text-xs sm:text-sm text-red-800 leading-relaxed font-medium">
                            {{ __('admin.super_admin_warning') }}
                        </p>
                        <form action="{{ route('admin.quotations.destroy', $quotation->id) }}" method="POST"
                              onsubmit="return confirm('PERMANENT ACTION: Are you sure you want to delete deal {{ $quotation->reference_number }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-black uppercase tracking-wider shadow-md transition-all cursor-pointer">
                                {{ __('admin.purge_deal_btn') }}
                            </button>
                        </form>
                    </div>
                @endcan

            </div>

        </div>

    </div>

</x-layouts.admin>
