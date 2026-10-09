<div>
    <!-- Backdrop with Dimming -->
    <div x-show="$wire.isOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-rn-bg-0/80 backdrop-blur-md z-50"
         @click="$wire.set('isOpen', false)"
         style="display: none;">
    </div>

    <!-- Drawer Container: Slide-over on Desktop & Bottom Sheet on Mobile -->
    <div x-show="$wire.isOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? '-translate-x-full md:-translate-x-full' : 'translate-x-full md:translate-x-full' }} translate-y-full md:translate-y-0"
         x-transition:enter-end="translate-x-0 translate-y-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0 translate-y-0"
         x-transition:leave-end="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? '-translate-x-full md:-translate-x-full' : 'translate-x-full md:translate-x-full' }} translate-y-full md:translate-y-0"
         @keydown.escape.window="$wire.set('isOpen', false)"
         role="dialog"
         aria-modal="true"
         aria-labelledby="cart-drawer-title"
         class="fixed z-50 bg-rn-bg-1/95 backdrop-blur-2xl text-rn-text-1
                /* Mobile : Bottom Sheet */
                inset-x-0 bottom-0 max-h-[90vh] rounded-t-3xl border-t border-rn-line/20 shadow-2xl flex flex-col
                /* Desktop : Slide-over Drawer */
                md:inset-y-0 md:inset-x-auto {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'md:left-0 md:border-e' : 'md:right-0 md:border-s' }} md:w-[480px] md:max-h-full md:rounded-none md:border-t-0 md:border-rn-line/20"
         style="display: none;">

        <!-- Header du Panier -->
        <div class="px-6 py-5 border-b border-rn-line/10 flex items-center justify-between bg-rn-bg-0/60">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl rn-glass-light border border-rn-line/15 flex items-center justify-center text-rn-gold shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h3 id="cart-drawer-title" class="text-base sm:text-lg font-bold text-rn-text-1">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'سلة طلب التسعيرة (RFQ)' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Dossier de Cotation (RFQ)' : 'Quotation Dossier (RFQ)') }}
                    </h3>
                    <p class="text-xs text-rn-text-3 font-mono mt-0.5">
                        {{ count($cart) }} {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'منتجات محددة' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'produit(s) sélectionné(s)' : 'item(s) selected') }}
                    </p>
                </div>
            </div>
            <button @click="$wire.set('isOpen', false)" 
                    class="w-11 h-11 rounded-xl rn-glass-light hover:border-rn-line/25 flex items-center justify-center text-rn-text-2 hover:text-rn-text-1 transition-colors shadow-sm cursor-pointer shrink-0"
                    aria-label="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'إغلاق السلة' : 'Close cart' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Liste des Produits dans le Panier -->
        <div class="flex-1 overflow-y-auto px-6 py-5 space-y-3.5">
            @forelse($cart as $item)
                <div class="p-4 rounded-2xl rn-glass-light border border-rn-line/12 hover:border-rn-gold/40 transition-all flex gap-3.5 relative group shadow-sm">
                    <div class="w-16 h-16 rounded-xl bg-rn-bg-0 border border-rn-line/10 flex items-center justify-center p-1 shrink-0 overflow-hidden shadow-sm">
                        @if(!empty($item['image']))
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['product_title'] }}" class="w-full h-full object-contain">
                        @else
                            <span class="text-2xl">🫒</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h4 class="text-sm font-bold text-rn-text-1 truncate">{{ $item['product_title'] }}</h4>
                            <button wire:click="removeItem('{{ $item['key'] }}')" 
                                    class="text-rn-text-3 hover:text-rose-400 transition-colors p-2 min-w-[44px] min-h-[44px] -m-2 flex items-center justify-center cursor-pointer shrink-0" 
                                    title="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'حذف' : 'Remove' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        @php
                            $pkgInfo = \App\Services\PackagingService::all()[$item['packaging_type']] ?? null;
                            $pkgName = $pkgInfo ? ($pkgInfo['name_' . app()->getLocale()] ?? $pkgInfo['name_en']) : str_replace('_', ' ', $item['packaging_type']);
                            $pkgIcon = $pkgInfo['icon_emoji'] ?? '📦';
                        @endphp
                        <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                            <span class="px-2.5 py-0.5 rounded-lg bg-rn-bg-0 border border-rn-line/10 text-xs font-semibold text-rn-gold flex items-center gap-1">
                                <span>{{ $pkgIcon }}</span>
                                <span>{{ $pkgName }}</span>
                            </span>
                            <span class="px-2.5 py-0.5 rounded-lg rn-glass-light border border-rn-line/10 text-xs text-rn-text-2 font-mono">
                                {{ $item['size'] }}
                            </span>
                            @if($item['is_private_label'])
                                <span class="px-2.5 py-0.5 rounded-lg bg-rn-olive/15 border border-rn-olive/30 text-rn-olive text-xs font-bold font-mono">
                                    Private Label
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-rn-line/8">
                            <span class="text-xs text-rn-text-3 font-semibold">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الكمية المطلوبة :' : 'Volume :' }}
                            </span>
                            <div class="flex items-center gap-2">
                                <input type="number" 
                                       wire:change="updateQuantity('{{ $item['key'] }}', $event.target.value)" 
                                       value="{{ $item['quantity'] }}" 
                                       min="1" 
                                       class="w-20 px-2.5 py-2 rounded-xl bg-rn-bg-0 border border-rn-line/15 text-center text-base font-mono font-bold text-rn-text-1 focus:border-rn-gold focus:outline-none shadow-sm">
                                <span class="text-xs text-rn-text-2 font-mono font-semibold">{{ $item['unit'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="h-64 flex flex-col items-center justify-center text-center p-6 space-y-4">
                    <div class="w-16 h-16 rounded-3xl rn-glass-light border border-rn-line/12 flex items-center justify-center text-3xl shadow-sm">
                        📦
                    </div>
                    <p class="text-sm text-rn-text-2 max-w-xs font-medium leading-relaxed">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                            ? 'سلة طلب التسعيرة فارغة حالياً. تصفح المنتجات واختر نوع وسعة التعليب المناسبة لصفقتك.' 
                            : 'Your quotation dossier is empty. Select packaging and sizes from our catalog to add items.' }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Footer & Actions (Sticky Bottom with Safe Area on Mobile) -->
        @if(count($cart) > 0)
            <div class="p-6 border-t border-rn-line/10 bg-rn-bg-0/80 space-y-4 pb-8 md:pb-6 shadow-2xl">
                <div class="flex items-center justify-between text-xs text-rn-text-3 pb-1 font-mono">
                    <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'نمط التسعير الدولي :' : 'Pricing Mode :' }}</span>
                    <span class="text-rn-gold font-bold">Custom Export RFQ (B2B)</span>
                </div>
                
                <a href="{{ LaravelLocalization::localizeUrl('/rfq') }}" 
                   class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-full bg-gradient-to-r from-rn-gold-soft to-rn-gold hover:from-rn-gold hover:to-rn-gold-soft text-rn-bg-0 font-bold text-sm shadow-lg shadow-rn-gold/25 transition-all hover:scale-[1.01] active:scale-[0.98] cursor-pointer">
                    <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'إرسال طلب التسعيرة الرسمي ➔' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Finaliser la Demande de Cotation ➔' : 'Proceed to Official RFQ ➔') }}</span>
                </a>

                <div class="flex items-center justify-between pt-1 text-xs text-rn-text-3">
                    <button wire:click="clearCart" class="hover:text-rose-400 transition-colors cursor-pointer py-2 min-h-[44px] inline-flex items-center">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تفريغ السلة' : 'Vider le dossier' }}
                    </button>
                    <span class="flex items-center gap-1 font-mono">
                        🔒 Confidential B2B Pricing
                    </span>
                </div>
            </div>
        @endif

    </div>
</div>
