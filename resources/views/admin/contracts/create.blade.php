<x-layouts.admin :title="'Contract Builder — Deal ' . $quotation->reference_number">

    <div class="max-w-4xl mx-auto space-y-8">
        
        <div class="flex items-center gap-3 border-b-2 border-slate-200 pb-4">
            <a href="{{ route('admin.quotations.show', $quotation->id) }}" class="text-sm font-extrabold text-gold-700 hover:text-gold-900 flex items-center gap-1">
                <span>← Back to Deal {{ $quotation->reference_number }}</span>
            </a>
            <span class="text-slate-400">•</span>
            <span class="text-xs sm:text-sm text-slate-500 font-medium">{{ __('admin.contract_step') }}</span>
        </div>

        <div>
            <span class="text-xs sm:text-sm uppercase tracking-widest text-emerald-800 font-black flex items-center gap-1.5">
                <span>⚖️</span>
                <span>{{ __('admin.dashboard_badge') }}</span>
            </span>
            <h1 class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1 tracking-tight">
                {{ __('admin.contract_builder_title') }}
            </h1>
            <p class="text-sm sm:text-base text-slate-600 mt-1">
                {{ __('admin.contract_builder_subtitle') }}
            </p>
        </div>

        <form action="{{ route('admin.contracts.store', $quotation->id) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-gold-400/30 shadow-2xl space-y-8">
            @csrf

            <!-- Section 1 : Identifiants & Termes -->
            <div class="space-y-4">
                <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">{{ __('admin.contract_sec1_title') }}</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">{{ __('admin.contract_number') }}</label>
                        <input type="text" name="contract_number" value="{{ old('contract_number', $suggestedNumber) }}" required
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-300 text-gold-800 font-mono font-black text-base focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">{{ __('admin.buyer_company_label') }}</label>
                        <input type="text" value="{{ $quotation->company_name }} ({{ $quotation->country }})" readonly
                               class="w-full px-4 py-3 rounded-2xl bg-slate-100 border-2 border-slate-200 text-slate-600 text-base font-bold cursor-not-allowed">
                    </div>
                </div>
            </div>

            <!-- Section 2 : Conditions Financières & Banque -->
            <div class="space-y-4 pt-6 border-t border-slate-100">
                <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">{{ __('admin.contract_sec2_title') }}</h2>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-800">{{ __('admin.payment_terms_clause') }}</label>
                    <textarea name="payment_terms" rows="2" required
                              class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('payment_terms', '30% Advance Wire Transfer (T/T) upon contract countersignature, 70% Irrevocable at sight Letter of Credit (L/C) or T/T against presentation of Clean On Board Ocean Bill of Lading.') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.bank_name_tn') }}</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', 'Banque Internationale Arabe de Tunisie (BIAT) — Tunis Corporate') }}" required
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.iban_tn') }}</label>
                        <input type="text" name="iban" value="{{ old('iban', 'TN59 0800 1234 5678 9012 3456') }}" required
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.swift_bic') }}</label>
                        <input type="text" name="swift_bic" value="{{ old('swift_bic', 'BIATTNTT') }}" required
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Section 3 : Documents de Douane et Expédition -->
            <div class="space-y-4 pt-6 border-t border-slate-100">
                <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">{{ __('admin.contract_sec3_title') }}</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-sm">
                    <label class="flex items-center gap-3 p-4 rounded-2xl bg-amber-50/40 border-2 border-gold-200 cursor-pointer hover:bg-amber-50/80 transition-all">
                        <input type="checkbox" name="required_documents[]" value="Commercial Invoice (3 Originals + 3 Copies)" checked class="w-5 h-5 rounded border-2 border-gold-500 text-gold-600 focus:ring-gold-400">
                        <span class="text-slate-800 font-bold">Commercial Invoice (3 Originals + 3 Copies)</span>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-2xl bg-amber-50/40 border-2 border-gold-200 cursor-pointer hover:bg-amber-50/80 transition-all">
                        <input type="checkbox" name="required_documents[]" value="Clean On Board Ocean Bill of Lading (3/3 Set)" checked class="w-5 h-5 rounded border-2 border-gold-500 text-gold-600 focus:ring-gold-400">
                        <span class="text-slate-800 font-bold">Clean On Board Ocean Bill of Lading (3/3 Set)</span>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-2xl bg-amber-50/40 border-2 border-gold-200 cursor-pointer hover:bg-amber-50/80 transition-all">
                        <input type="checkbox" name="required_documents[]" value="EUR.1 Movement Certificate / Certificate of Origin" checked class="w-5 h-5 rounded border-2 border-gold-500 text-gold-600 focus:ring-gold-400">
                        <span class="text-slate-800 font-bold">EUR.1 Movement Certificate / Certificate of Origin</span>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-2xl bg-amber-50/40 border-2 border-gold-200 cursor-pointer hover:bg-amber-50/80 transition-all">
                        <input type="checkbox" name="required_documents[]" value="Phytosanitary & Sanitary Health Certificate (Tunisian Ministry)" checked class="w-5 h-5 rounded border-2 border-gold-500 text-gold-600 focus:ring-gold-400">
                        <span class="text-slate-800 font-bold">Phytosanitary & Sanitary Health Certificate</span>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-2xl bg-amber-50/40 border-2 border-gold-200 cursor-pointer hover:bg-amber-50/80 transition-all">
                        <input type="checkbox" name="required_documents[]" value="Certificate of Analysis & COI Quality Lab Assay" checked class="w-5 h-5 rounded border-2 border-gold-500 text-gold-600 focus:ring-gold-400">
                        <span class="text-slate-800 font-bold">Certificate of Analysis & COI Quality Lab Assay</span>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-2xl bg-amber-50/40 border-2 border-gold-200 cursor-pointer hover:bg-amber-50/80 transition-all">
                        <input type="checkbox" name="required_documents[]" value="Packing List & Weight Calibration Certificate" checked class="w-5 h-5 rounded border-2 border-gold-500 text-gold-600 focus:ring-gold-400">
                        <span class="text-slate-800 font-bold">Packing List & Weight Calibration Certificate</span>
                    </label>
                </div>

                <div class="space-y-2 pt-2">
                    <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.dhl_tracking') }}</label>
                    <input type="text" name="dhl_tracking_number" placeholder="e.g. DHL-984321765"
                           class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                </div>
            </div>

            <!-- Submit Action -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('admin.quotations.show', $quotation->id) }}" class="text-sm font-extrabold text-slate-600 hover:text-slate-900">
                    ← {{ __('admin.cancel') }}
                </a>

                <button type="submit" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-emerald-600 hover:from-emerald-500 hover:to-emerald-400 text-white font-black text-sm sm:text-base uppercase tracking-wider shadow-xl shadow-emerald-600/25 ring-2 ring-emerald-400 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span>{{ __('admin.generate_contract_btn') }}</span>
                </button>
            </div>
        </form>

    </div>

</x-layouts.admin>
