<x-layouts.app :title="LaravelLocalization::getCurrentLocale() === 'ar' ? 'طلب تسعيرة تصدير رسمية (RFQ) — Rayanova T.D' : 'Official Export Quotation Request (RFQ) — Rayanova T.D'">

    <section class="py-12 md:py-20 relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <!-- En-tête de Page (Haute Visibilité & Typographie Nette) -->
            <div class="text-center max-w-3xl mx-auto space-y-5">
                <span class="inline-flex items-center gap-2.5 px-6 py-2.5 rounded-full bg-slate-900/90 backdrop-blur-md border-2 border-gold-400 text-sm sm:text-base font-black text-gold-300 uppercase tracking-widest shadow-2xl">
                    <span class="w-3 h-3 rounded-full bg-gold-400 animate-pulse"></span>
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'بوابة التصدير والصفقات الدولية 2026' : 'International B2B Agro-Export & Quotation Engine' }}
                </span>
                <h1 class="font-serif text-3xl sm:text-5xl md:text-6xl font-black text-white tracking-tight leading-tight drop-shadow-xl">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'طلب تسعيرة تصدير رسمية (RFQ)' : 'Request for Quotation (RFQ)' }}
                </h1>
                <p class="text-base sm:text-lg text-slate-200 font-semibold leading-relaxed max-w-2xl mx-auto drop-shadow-md">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                        ? 'املأ البيانات التجارية لشركتك، وسيقوم مكتب التصدير بإعداد فاتورة تقديرية (Pro-Forma Invoice) مفصلة بأسعار البورصة ومواصفات الشحن.'
                        : 'Submit your commercial company details and trade terms. Our export desk will review vessel schedules and issue an official pro-forma invoice.' }}
                </p>
            </div>

            <!-- Formulaire RFQ dans une Carte Blanche Haute Définition -->
            <form action="{{ route('rfq.store', ['locale' => LaravelLocalization::getCurrentLocale()]) }}" method="POST" class="bg-white rounded-3xl p-8 sm:p-14 border-2 border-gold-500/70 shadow-2xl space-y-12">
                @csrf

                @if(isset($errors) && $errors->any())
                    <div class="p-6 rounded-2xl bg-red-50 border-2 border-red-500 text-red-950 text-base font-bold space-y-2 shadow-md">
                        @foreach($errors->all() as $error)
                            <p class="flex items-center gap-2.5">⚠️ <span>{{ $error }}</span></p>
                        @endforeach
                    </div>
                @endif

                <!-- Section 1 : Type de Requête -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-amber-500 text-slate-950 font-black text-sm flex items-center justify-center shrink-0 shadow-sm">01</span>
                        <label class="text-base sm:text-lg font-black uppercase tracking-wider text-slate-950">
                            {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'طبيعة الطلب والتوريد :' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Nature de la Requête :' : 'Request Type :') }}
                        </label>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <label class="relative flex items-center gap-4 p-5 sm:p-6 rounded-2xl bg-amber-50/60 border-2 border-slate-300 hover:border-amber-600 hover:bg-white cursor-pointer transition-all shadow-sm">
                            <input type="radio" name="request_type" value="quote" checked class="w-6 h-6 text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="block text-base sm:text-lg font-black text-slate-950">
                                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'عرض أسعار تجاري' : 'Commercial Quote' }}
                                </span>
                                <span class="block text-sm text-slate-700 font-bold mt-1">
                                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'شحنات حاويات FCL / بحري صب' : 'Container FCL / Sea Bulk' }}
                                </span>
                            </div>
                        </label>
                        <label class="relative flex items-center gap-4 p-5 sm:p-6 rounded-2xl bg-amber-50/60 border-2 border-slate-300 hover:border-amber-600 hover:bg-white cursor-pointer transition-all shadow-sm">
                            <input type="radio" name="request_type" value="sample_request" class="w-6 h-6 text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="block text-base sm:text-lg font-black text-slate-950">
                                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'عينة تجريبية (DHL)' : 'Sample Box (DHL)' }}
                                </span>
                                <span class="block text-sm text-slate-700 font-bold mt-1">
                                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'طرد مخبري وتحليلي للتذوق' : 'Lab testing & tasting kit' }}
                                </span>
                            </div>
                        </label>
                        <label class="relative flex items-center gap-4 p-5 sm:p-6 rounded-2xl bg-amber-50/60 border-2 border-slate-300 hover:border-amber-600 hover:bg-white cursor-pointer transition-all shadow-sm">
                            <input type="radio" name="request_type" value="private_label" class="w-6 h-6 text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="block text-base sm:text-lg font-black text-slate-950">
                                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'علامة خاصة (OEM)' : 'Private Label (OEM)' }}
                                </span>
                                <span class="block text-sm text-slate-700 font-bold mt-1">
                                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تغليف وتعليب مخصص للمشتري' : 'Custom buyer packaging' }}
                                </span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Section 2 : Données de l'Importateur & Entreprise -->
                <div class="space-y-6 pt-10 border-t-2 border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-amber-500 text-slate-950 font-black text-sm flex items-center justify-center shrink-0 shadow-sm">02</span>
                        <label class="text-base sm:text-lg font-black uppercase tracking-wider text-slate-950">
                            {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'بيانات الشركة المستوردة :' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Données de la Société Importatrice :' : 'Importer & Company Credentials :') }}
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الاسم القانوني للشركة المستوردة *' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Raison Sociale de l\'Entreprise *' : 'Company Legal Name *') }}
                            </label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" required 
                                   placeholder="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مثال: شركة الخليج للأغذية أو Nordic Gourmet Import GmbH' : 'e.g. Nordic Gourmet Import GmbH' }}" 
                                   class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-300 text-slate-950 font-bold text-base sm:text-lg placeholder-slate-400 focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'اسم مسؤول المشتريات / جهة الاتصال *' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Nom du Responsable Achats *' : 'Contact Person (Full Name) *') }}
                            </label>
                            <input type="text" name="contact_name" value="{{ old('contact_name') }}" required 
                                   placeholder="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مثال: أحمد المنصوري (مدير التوريد) أو Hans Gruber' : 'e.g. Hans Gruber (Procurement Director)' }}" 
                                   class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-300 text-slate-950 font-bold text-base sm:text-lg placeholder-slate-400 focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'البريد الإلكتروني المهني للشركة *' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Adresse Email Professionnelle *' : 'Corporate Email Address *') }}
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                   placeholder="purchasing@company.com" 
                                   class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-300 text-slate-950 font-bold text-base sm:text-lg placeholder-slate-400 focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'رقم الهاتف أو واتساب للتواصل السريع' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Numéro de Téléphone / WhatsApp' : 'WhatsApp / Phone Number') }}
                            </label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" 
                                   placeholder="+971 50 123 4567 / +49 170 9988776" 
                                   class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-300 text-slate-950 font-bold text-base sm:text-lg placeholder-slate-400 focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'دولة الاستيراد / الوجهة *' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Pays de Destination / Importation *' : 'Country of Importation *') }}
                            </label>
                            <input type="text" name="country" value="{{ old('country') }}" required 
                                   placeholder="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مثال: الإمارات، السعودية، ألمانيا، فرنسا، كندا' : 'e.g. Germany, France, United States, UAE, Saudi Arabia' }}" 
                                   class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-300 text-slate-950 font-bold text-base sm:text-lg placeholder-slate-400 focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'المعرف الجبائي أو السجل التجاري للشركة' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Numéro d\'Identification Fiscale / TVA' : 'Tax ID / VAT Registration Number') }}
                            </label>
                            <input type="text" name="tax_number" value="{{ old('tax_number') }}" 
                                   placeholder="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مثال: الرقم الضريبي أو السجل التجاري' : 'e.g. DE-123456789 or Tax Registration ID' }}" 
                                   class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-300 text-slate-950 font-bold text-base sm:text-lg placeholder-slate-400 focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Section 3 : Modalités de Livraison & Incoterms 2020 -->
                <div class="space-y-6 pt-10 border-t-2 border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-amber-500 text-slate-950 font-black text-sm flex items-center justify-center shrink-0 shadow-sm">03</span>
                        <label class="text-base sm:text-lg font-black uppercase tracking-wider text-slate-950">
                            {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'شروط الشحن والتسليم الدولي (Incoterms 2020) :' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Conditions d\'Expédition (Incoterms 2020) :' : 'Shipping Terms & Incoterms 2020 :') }}
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'شرط التسليم المطلوب (Incoterm) *' : 'Desired Incoterm *' }}
                            </label>
                            <select name="incoterm" class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-300 text-slate-950 font-bold text-base sm:text-lg focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm cursor-pointer">
                                <option value="FOB">FOB (Free on Board — Port of Rades/Sfax)</option>
                                <option value="CIF" selected>CIF (Cost, Insurance & Freight to your port)</option>
                                <option value="CFR">CFR (Cost & Freight)</option>
                                <option value="EXW">EXW (Ex Works — Mill Gateway)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'ميناء أو مطار الوصول المقصود *' : 'Destination Port / Airport *' }}
                            </label>
                            <input type="text" name="destination_port" value="{{ old('destination_port') }}" required 
                                   placeholder="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مثال: ميناء جبل علي، ميناء هامبورغ، ميناء مرسيليا' : 'e.g. Port of Hamburg, Port of Marseille, Jebel Ali' }}" 
                                   class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-300 text-slate-950 font-bold text-base sm:text-lg placeholder-slate-400 focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'طريقة الشحن واللوجستيات *' : 'Logistics Mode *' }}
                            </label>
                            <select name="shipping_method" class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-300 text-slate-950 font-bold text-base sm:text-lg focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm cursor-pointer">
                                <option value="sea_fcl" selected>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'شحن بحري حاويات كاملة (FCL 20ft / 40ft)' : 'Sea Freight 20ft / 40ft (FCL)' }}</option>
                                <option value="sea_lcl">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'شحن بحري مجمع جزئي (LCL)' : 'Sea Freight Consolidated (LCL)' }}</option>
                                <option value="air">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'شحن جوي سريع (Air Cargo)' : 'Air Cargo Express' }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 4 : Récapitulatif des Produits du Panier -->
                <div class="space-y-6 pt-10 border-t-2 border-slate-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-amber-500 text-slate-950 font-black text-sm flex items-center justify-center shrink-0 shadow-sm">04</span>
                            <label class="text-base sm:text-lg font-black uppercase tracking-wider text-slate-950">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'المنتجات والكميات المحددة :' : 'Selected Products & Packaging :' }}
                            </label>
                        </div>
                        <a href="{{ LaravelLocalization::localizeUrl('/#products') }}" class="text-sm sm:text-base font-black text-amber-800 hover:text-amber-950 underline flex items-center gap-1.5">
                            <span>+ {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'إضافة منتجات أخرى من الكتالوج' : 'Add more items from catalog' }}</span>
                        </a>
                    </div>

                    @if(!empty($cart) && count($cart) > 0)
                        <div class="divide-y divide-slate-300 rounded-3xl bg-amber-50/50 border-2 border-gold-400 p-6 sm:p-8 shadow-sm">
                            @foreach($cart as $item)
                                <div class="py-4 flex flex-wrap items-center justify-between gap-4 text-sm sm:text-base">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-white border-2 border-gold-300 p-1 flex items-center justify-center shadow-sm shrink-0">
                                            @if(!empty($item['image']))
                                                <img src="{{ asset($item['image']) }}" alt="{{ $item['product_title'] }}" class="w-full h-full object-contain">
                                            @else
                                                <span class="text-2xl">🫒</span>
                                            @endif
                                        </div>
                                        <div>
                                            <strong class="text-slate-950 font-black text-base sm:text-lg block">{{ $item['product_title'] }}</strong>
                                            <div class="text-slate-700 text-sm font-bold mt-1 flex items-center gap-2.5">
                                                <span class="px-2.5 py-1 rounded-lg bg-amber-100 border border-amber-400 text-amber-950 font-black uppercase text-xs">{{ ucwords(str_replace('_', ' ', $item['packaging_type'])) }}</span>
                                                <span>• {{ $item['size'] }}</span>
                                                @if(!empty($item['is_private_label']))
                                                    <span class="text-emerald-700 font-black">(Private Label OEM)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-slate-950 font-mono font-black text-lg sm:text-xl px-5 py-2.5 rounded-2xl bg-white border-2 border-gold-400 shadow-sm">
                                        {{ number_format($item['quantity'], 0) }} {{ $item['unit'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Sélecteur rapide si panier vide -->
                        <div class="p-6 sm:p-8 rounded-3xl bg-amber-50/50 border-2 border-slate-400 space-y-5 shadow-sm">
                            <p class="text-sm sm:text-base text-slate-800 font-bold">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                    ? 'سلة طلب التسعيرة فارغة حالياً. يمكنك اختيار منتج رئيسي من القائمة أدناه أو تصفح كتالوج المنتجات:' 
                                    : 'Your quotation dossier is currently empty. You can select a product below or browse our interactive catalog:' }}
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'حدد المنتج الرئيسي المطلوب *' : 'Select Primary Commodity *' }}
                                    </label>
                                    <select name="single_product_id" class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-400 text-slate-950 font-bold text-base sm:text-lg focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none shadow-sm cursor-pointer">
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}">{{ $p->localized_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm sm:text-base font-black text-slate-950 mb-2.5">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الكمية التقديرية *' : 'Estimated Quantity *' }}
                                    </label>
                                    <input type="number" name="single_quantity" value="1000" min="1" class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-400 text-slate-950 font-bold text-base sm:text-lg focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none shadow-sm">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Section 5 : Notes & Spécifications Additionnelles -->
                <div class="space-y-4 pt-10 border-t-2 border-slate-200">
                    <label class="block text-base sm:text-lg font-black text-slate-950">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'ملاحظات أو متطلبات خاصة (تحاليل مخبرية محددة، مهلة التسليم، شهادات إضافية) :' : 'Special Requirements & Notes (Specific COI parameters, target dates, certifications) :' }}
                    </label>
                    <textarea name="client_notes" rows="4" placeholder="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'أضف أي متطلبات خاصة (مثل: نسبة حموضة محددة، ملصقات مخصصة، جدول شحن مجدول)...' : 'Mention any specific requirements (e.g. customized labels, specific acidity limit, delivery schedule)...' }}" class="w-full px-5 py-4 rounded-2xl bg-white border-2 border-slate-400 text-slate-950 font-bold text-base sm:text-lg placeholder-slate-400 focus:border-amber-600 focus:ring-4 focus:ring-amber-400/20 focus:outline-none transition-all shadow-sm"></textarea>
                </div>

                <!-- Bouton de Soumission Principal (Haute Visibilité & Style Vibrant) -->
                <div class="pt-8">
                    <button type="submit" class="w-full py-6 px-10 rounded-2xl bg-gradient-to-r from-gold-500 via-amber-400 to-gold-500 hover:from-gold-400 hover:to-amber-300 text-slate-950 font-black text-lg sm:text-xl md:text-2xl uppercase tracking-wider shadow-2xl shadow-gold-500/40 border-2 border-gold-600 ring-4 ring-gold-400/30 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-4 cursor-pointer">
                        <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'إرسال طلب التسعيرة الرسمي الآن ➔' : 'Submit Official Export Quotation Request ➔' }}</span>
                    </button>
                    <p class="text-center text-sm sm:text-base text-slate-700 font-bold mt-5 flex items-center justify-center gap-2.5">
                        <span class="text-lg">🔒</span>
                        <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'معالجة تجارية سرية ومحمية B2B • الرد خلال 24 ساعة عمل' : 'Confidential & Secure B2B Trade Processing • Response typically within 24 business hours' }}</span>
                    </p>
                </div>

            </form>

        </div>
    </section>

</x-layouts.app>
