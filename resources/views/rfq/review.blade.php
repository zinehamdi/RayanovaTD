<x-layouts.app :title="'Official Quotation Review — ' . $quotation->reference_number">

    <section class="py-12 md:py-20 relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            @if(session('success'))
                <div class="p-6 rounded-2xl bg-emerald-50 border-2 border-emerald-500 text-emerald-950 text-base font-black flex items-center gap-3 shadow-lg">
                    <span class="text-3xl">🎉</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- En-tête du Dossier -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 p-8 sm:p-12 rounded-3xl bg-white border-2 border-gold-500/60 shadow-2xl">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-black uppercase tracking-wider
                            {{ $quotation->status === 'confirmed' ? 'bg-emerald-600 text-white shadow-md' : ($quotation->status === 'quoted' || $quotation->status === 'client_viewed' ? 'bg-gold-500 text-slate-950 shadow-md' : 'bg-slate-900 text-white') }}">
                            ● Status : {{ strtoupper(str_replace('_', ' ', $quotation->status)) }}
                        </span>
                        <span class="text-sm font-mono font-black text-slate-800 px-3.5 py-1 bg-slate-100 rounded-xl border-2 border-slate-300">{{ $quotation->reference_number }}</span>
                    </div>
                    <h1 class="font-serif text-3xl sm:text-5xl font-black text-slate-950">
                        {{ $quotation->company_name }}
                    </h1>
                    <p class="text-base sm:text-lg text-slate-800 font-bold mt-2">
                        Attn : {{ $quotation->contact_name }} • {{ $quotation->country }} • Port : {{ $quotation->destination_port }}
                    </p>
                </div>

                <div class="text-start md:text-end space-y-2 p-6 rounded-2xl bg-amber-50/70 border-2 border-gold-400 shadow-sm">
                    <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-700 block">Total Quotation ({{ $quotation->incoterm }})</span>
                    <div class="text-3xl sm:text-4xl font-black text-slate-950 font-mono">
                        {{ $quotation->total_amount ? number_format($quotation->total_amount, 2) . ' ' . $quotation->currency : 'Pricing Pending' }}
                    </div>
                    @if($quotation->valid_until)
                        <span class="text-xs sm:text-sm text-slate-700 block font-bold">
                            Valid until : <strong class="text-slate-950 font-black">{{ $quotation->valid_until->format('d M Y') }}</strong>
                        </span>
                    @endif
                    <div class="pt-2">
                        <a href="{{ route('quote.download_pdf', ['locale' => LaravelLocalization::getCurrentLocale(), 'reference' => $quotation->reference_number, 'token' => $quotation->signed_token]) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white hover:bg-amber-100 border-2 border-gold-500 text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-sm transition-all hover:scale-105 cursor-pointer">
                            <span>📄</span>
                            <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تحميل عرض السعر (PDF)' : 'Download Quotation PDF' }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Détail des Lignes de Cotation -->
            <div class="bg-white rounded-3xl p-8 sm:p-12 border-2 border-gold-500/60 shadow-2xl space-y-8">
                <div class="flex items-center justify-between pb-5 border-b-2 border-slate-200">
                    <h3 class="font-serif text-2xl font-black text-slate-950 flex items-center gap-2.5">
                        <span>📦</span>
                        <span>Quotation Line Items & Specifications</span>
                    </h3>
                    <span class="text-sm sm:text-base text-gold-950 font-black px-4 py-1.5 rounded-xl bg-amber-100 border-2 border-gold-400">Currency : {{ $quotation->currency }}</span>
                </div>

                <div class="divide-y divide-slate-200 overflow-x-auto">
                    <table class="w-full text-start text-base">
                        <thead>
                            <tr class="text-slate-800 uppercase tracking-wider text-xs sm:text-sm pb-4 border-b-2 border-slate-300">
                                <th class="text-start py-4 font-black">Commodity / Product</th>
                                <th class="text-start py-4 font-black">Packaging & Size</th>
                                <th class="text-center py-4 font-black">Quantity</th>
                                <th class="text-end py-4 font-black">Offered Unit Price</th>
                                <th class="text-end py-4 font-black">Line Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($quotation->items as $item)
                                <tr class="hover:bg-amber-50/50 transition-colors">
                                    <td class="py-5 font-black text-slate-950 text-base sm:text-lg">
                                        {{ $item->product->localized_title ?? 'Product' }}
                                        @if($item->is_private_label)
                                            <span class="block text-xs sm:text-sm text-emerald-700 font-black">Private Label OEM</span>
                                        @endif
                                    </td>
                                    <td class="py-5 text-slate-800 font-bold text-sm sm:text-base">
                                        {{ ucwords(str_replace('_', ' ', $item->packaging_type)) }}<br>
                                        <span class="text-xs sm:text-sm text-gold-900 font-mono font-black">{{ $item->size }}</span>
                                    </td>
                                    <td class="py-5 text-center font-mono font-black text-slate-950 text-base sm:text-lg">
                                        {{ number_format($item->quantity, 0) }} {{ $item->unit }}
                                    </td>
                                    <td class="py-5 text-end font-mono text-slate-800 font-bold text-base">
                                        {{ $item->offered_unit_price ? number_format($item->offered_unit_price, 2) . ' ' . $quotation->currency : 'Under Review' }}
                                    </td>
                                    <td class="py-5 text-end font-mono font-black text-gold-950 text-lg sm:text-xl">
                                        {{ $item->subtotal ? number_format($item->subtotal, 2) . ' ' . $quotation->currency : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Total Récapitulatif -->
                @if($quotation->total_amount)
                    <div class="p-6 sm:p-8 rounded-2xl bg-amber-50/60 border-2 border-gold-400 flex flex-col sm:flex-row items-center justify-between gap-5">
                        <div class="text-sm sm:text-base text-slate-800 space-y-1.5 font-bold">
                            <p>• Incoterm : <strong class="text-slate-950 font-black">{{ $quotation->incoterm }} ({{ $quotation->destination_port }})</strong></p>
                            <p>• Quality Inspection : COI Lab Analysis Included</p>
                            <p>• Commercial Terms : International Sales Contract to be issued upon confirmation</p>
                        </div>
                        <div class="text-end">
                            <span class="text-xs sm:text-sm uppercase tracking-wider text-slate-700 block font-black">Total Pro-Forma Value</span>
                            <span class="text-3xl sm:text-4xl font-black text-gold-950 font-mono">{{ number_format($quotation->total_amount, 2) }} {{ $quotation->currency }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- BOUTONS D'ACTION CLIENT -->
            @if($quotation->status !== 'confirmed' && $quotation->status !== 'contract_issued' && $quotation->status !== 'closed')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Bouton 1 : Confirmer l'Offre -->
                    <form action="{{ route('quote.confirm', ['locale' => LaravelLocalization::getCurrentLocale(), 'reference' => $quotation->reference_number]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $quotation->signed_token }}">
                        <button type="submit" 
                                onclick="return confirm('Do you confirm this quotation to proceed with the official sales contract?')"
                                class="w-full py-5 px-8 rounded-2xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-emerald-600 hover:from-emerald-500 hover:to-emerald-400 text-white font-black text-base sm:text-lg border-2 border-emerald-400 shadow-xl shadow-emerald-600/35 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-3 cursor-pointer">
                            <span>✅</span>
                            <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تأكيد الصفقة وقبول التسعيرة ➔' : 'Confirm Deal & Issue Official Contract ➔' }}</span>
                        </button>
                    </form>

                    <!-- Bouton 2 : Demander un Ajustement (Négociation) -->
                    <div x-data="{ openModal: false }">
                        <button type="button" 
                                @click="openModal = true" 
                                class="w-full py-5 px-8 rounded-2xl bg-white hover:bg-gold-50 border-2 border-gold-500 text-slate-950 font-black text-base sm:text-lg shadow-md transition-all hover:scale-[1.01] flex items-center justify-center gap-3 cursor-pointer">
                            <span>✍️</span>
                            <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'طلب تعديل الكميات أو الشروط' : 'Request Volume / Price Amendment' }}</span>
                        </button>

                        <!-- Modal d'Ajustement -->
                        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md" style="display: none;">
                            <div @click.outside="openModal = false" class="w-full max-w-lg bg-white border-2 border-gold-400 rounded-3xl p-8 space-y-6 shadow-2xl">
                                <h3 class="font-serif text-2xl sm:text-3xl font-black text-slate-950">Request Quotation Amendment</h3>
                                <p class="text-sm sm:text-base text-slate-700 font-bold">
                                    State the adjustments you wish to make (e.g. modify order volume, change Incoterm to FOB, or request another packaging type):
                                </p>
                                <form action="{{ route('quote.modify', ['locale' => LaravelLocalization::getCurrentLocale(), 'reference' => $quotation->reference_number]) }}" method="POST" class="space-y-5">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $quotation->signed_token }}">
                                    <textarea name="amendment_notes" rows="5" required placeholder="Write your amendment details..." class="w-full p-4 rounded-2xl bg-white border-2 border-slate-400 text-base font-bold text-slate-950 placeholder-slate-400 focus:border-gold-600 focus:outline-none"></textarea>
                                    <div class="flex items-center justify-end gap-3 pt-2">
                                        <button type="button" @click="openModal = false" class="px-6 py-3 rounded-2xl text-sm sm:text-base font-black text-slate-700 hover:text-slate-950 border-2 border-slate-300">Cancel</button>
                                        <button type="submit" class="px-7 py-3 rounded-2xl bg-gold-500 hover:bg-gold-400 text-slate-950 font-black text-sm sm:text-base border-2 border-gold-600 shadow-md">Submit Amendment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton 3 : Télécharger l'Offre Officielle en PDF -->
                    <a href="{{ route('quote.download_pdf', ['locale' => LaravelLocalization::getCurrentLocale(), 'reference' => $quotation->reference_number, 'token' => $quotation->signed_token]) }}"
                       target="_blank"
                       class="w-full py-5 px-8 rounded-2xl bg-amber-400 hover:bg-amber-300 border-2 border-gold-600 text-slate-950 font-black text-base sm:text-lg shadow-md transition-all hover:scale-[1.01] flex items-center justify-center gap-3 cursor-pointer">
                        <span>📄</span>
                        <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تحميل وثيقة عرض السعر الرسمية (PDF)' : 'Download Official Quotation Document (PDF)' }}</span>
                    </a>

                </div>
            @else
                <!-- Contrat Officiel Disponible -->
                <div class="p-8 rounded-3xl bg-emerald-50 border-2 border-emerald-400 flex flex-col sm:flex-row items-center justify-between gap-5 text-base shadow-lg">
                    <div>
                        <span class="font-black text-emerald-950 text-lg sm:text-xl block">🎉 Deal Confirmed & Official Contract In Progress</span>
                        <span class="text-slate-800 font-bold mt-1 block">Our legal and export departments are generating the bilingual international sales contract with banking coordinates.</span>
                    </div>
                    <a href="https://wa.me/21698000000" target="_blank" class="px-7 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm sm:text-base border-2 border-emerald-300 shadow-md shrink-0">
                        💬 Contact Trade Officer
                    </a>
                </div>
            @endif

            <!-- Fil de Discussion & Audit Trail de la Négociation -->
            <div class="bg-white rounded-3xl p-8 sm:p-12 border-2 border-gold-500/60 shadow-2xl space-y-5">
                <h3 class="font-serif text-xl sm:text-2xl font-black text-slate-950 flex items-center gap-2.5">
                    <span>💬</span>
                    <span>Audit Trail & Deal Communications</span>
                </h3>
                
                <div class="space-y-4">
                    @forelse($quotation->messages as $msg)
                        <div class="p-6 rounded-2xl border-2 text-base space-y-2 {{ $msg->sender_type === 'client' ? 'bg-slate-50 border-slate-300' : 'bg-amber-50/70 border-gold-300' }}">
                            <div class="flex items-center justify-between text-xs sm:text-sm">
                                <span class="font-black {{ $msg->sender_type === 'client' ? 'text-slate-950' : 'text-gold-950' }}">
                                    {{ $msg->sender_name }} ({{ strtoupper($msg->sender_type) }})
                                </span>
                                <span class="text-slate-600 font-bold">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-900 leading-relaxed font-semibold">{{ $msg->message_body }}</p>
                        </div>
                    @empty
                        <p class="text-base text-slate-600 font-bold">No communication logs recorded yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

</x-layouts.app>
