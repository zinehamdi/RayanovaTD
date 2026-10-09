<x-layouts.app :title="'Quotation Request Submitted — ' . $quotation->reference_number">

    <section class="py-16 md:py-24 relative">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 sm:p-14 border-2 border-gold-500/70 shadow-2xl text-center space-y-9 relative overflow-hidden">
                
                <div class="w-24 h-24 mx-auto rounded-3xl bg-emerald-50 border-2 border-emerald-400 flex items-center justify-center text-4xl shadow-xl shadow-emerald-500/25">
                    🌿
                </div>

                <div class="space-y-4">
                    <span class="inline-block px-5 py-2 rounded-full bg-emerald-100 border-2 border-emerald-400 text-emerald-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-sm">
                        ● Dossier Successfully Registered
                    </span>
                    <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-black text-slate-950 leading-tight">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تم استلام طلب التسعيرة بنجاح' : 'Quotation Request Received' }}
                    </h1>
                    <p class="text-base sm:text-lg text-slate-800 font-bold max-w-xl mx-auto leading-relaxed">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar'
                            ? 'شكراً لاهتمامكم بمنتجات شركة Rayanova T.D. تم تسجيل ملف طلبكم بنجاح وهو قيد الدراسة والتحليل من قبل مكتب التصدير الدولي.'
                            : 'Thank you for your interest in Rayanova T.D commodities. Your request has been assigned to our export desk for pricing and shipping analysis.' }}
                    </p>
                </div>

                <!-- Référence & Détails Dossier -->
                <div class="p-6 sm:p-8 rounded-3xl bg-amber-50/70 border-2 border-gold-400 text-start space-y-4 max-w-lg mx-auto text-base shadow-sm">
                    <div class="flex items-center justify-between pb-3 border-b-2 border-gold-300/60">
                        <span class="text-slate-700 font-bold">Tracking Reference :</span>
                        <strong class="text-gold-950 font-mono font-black text-lg">{{ $quotation->reference_number }}</strong>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b-2 border-gold-300/60">
                        <span class="text-slate-700 font-bold">Importer / Company :</span>
                        <span class="text-slate-950 font-black text-base">{{ $quotation->company_name }} ({{ $quotation->country }})</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b-2 border-gold-300/60">
                        <span class="text-slate-700 font-bold">Incoterm & Port :</span>
                        <span class="text-gold-950 font-black text-base">{{ $quotation->incoterm }} — {{ $quotation->destination_port }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-700 font-bold">Notification Sent To :</span>
                        <span class="text-slate-950 font-mono font-bold">{{ $quotation->email }}</span>
                    </div>
                </div>

                <!-- Prochaines Étapes -->
                <div class="pt-6 border-t-2 border-slate-200 text-sm sm:text-base text-slate-800 space-y-3.5 max-w-lg mx-auto text-start font-medium">
                    <h4 class="font-black text-slate-950 uppercase tracking-wider text-sm flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-gold-600"></span>
                        <span>What Happens Next :</span>
                    </h4>
                    <p class="leading-relaxed font-semibold">1. Our trade desk calculates freight rates to <strong class="text-slate-950 font-black">{{ $quotation->destination_port }}</strong> and COI certified lab lot allocations.</p>
                    <p class="leading-relaxed font-semibold">2. You will receive an official pro-forma quotation with an encrypted <strong>Magic Link</strong> allowing you to review, adjust volumes, or approve the deal online in 1 click.</p>
                    <p class="leading-relaxed font-semibold">3. If you requested a sample box, a DHL tracking code will be dispatched to your email.</p>
                </div>

                <!-- Actions Rapides -->
                <div class="flex flex-wrap items-center justify-center gap-4 pt-6">
                    <a href="{{ LaravelLocalization::localizeUrl('/') }}" class="px-8 py-4 rounded-2xl bg-white hover:bg-slate-50 border-2 border-slate-400 text-slate-950 font-black text-base shadow-sm hover:border-gold-500 transition-all">
                        ← {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'العودة للصفحة الرئيسية' : 'Return to Homepage' }}
                    </a>
                    <a href="https://wa.me/21698000000?text={{ urlencode('Hello, I submitted RFQ ' . $quotation->reference_number . ' for ' . $quotation->company_name) }}" target="_blank" class="px-8 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-base border-2 border-emerald-400 shadow-xl shadow-emerald-600/35 transition-all flex items-center gap-2.5">
                        <span>💬 Follow-up on WhatsApp</span>
                    </a>
                </div>

            </div>
        </div>
    </section>

</x-layouts.app>
