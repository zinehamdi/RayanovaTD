<x-layouts.admin>
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border border-gold-500/20 rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-gold-500/20 text-gold-300 border border-gold-400/30 mb-2">
                        🛡️ {{ __('admin.account_security') ?? 'أمان الحساب والملف الشخصي' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-white">
                        {{ __('admin.profile_settings') ?? 'إعدادات الملف الشخصي وتغيير كلمة المرور' }}
                    </h1>
                    <p class="text-sm text-slate-300 mt-1">
                        {{ __('admin.profile_settings_subtitle') ?? 'تعديل بيانات حسابك وتحديث كلمة المرور لضمان أمان المنصة.' }}
                    </p>
                </div>

                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-white/5 border border-white/10 text-slate-200">
                    <span class="text-sm font-bold">{{ auth()->user()->email }}</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black {{ auth()->user()->hasRole('super_admin') ? 'bg-gold-500 text-slate-950' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30' }}">
                        {{ auth()->user()->hasRole('super_admin') ? '👑 Super Admin' : '💼 Admin' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form 1: Profile Information -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-gold-100 text-gold-700 flex items-center justify-center text-xl font-black">
                        👤
                    </span>
                    <div>
                        <h2 class="text-lg font-black text-slate-900">
                            {{ __('admin.personal_info') ?? 'المعلومات الشخصية' }}
                        </h2>
                        <p class="text-xs text-slate-500">
                            {{ __('admin.personal_info_desc') ?? 'تعديل الاسم وعنوان البريد الإلكتروني للحساب.' }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-black uppercase text-slate-700 mb-2">
                            {{ __('admin.full_name') ?? 'الاسم الكامل' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $user->name) }}" 
                               required
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 text-slate-900 text-sm font-semibold transition-all">
                        @error('name')
                            <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-black uppercase text-slate-700 mb-2">
                            {{ __('admin.email_address') ?? 'البريد الإلكتروني' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $user->email) }}" 
                               required
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 text-slate-900 text-sm font-semibold transition-all">
                        @error('email')
                            <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm transition-all shadow-md cursor-pointer flex items-center gap-2">
                            <span>💾</span>
                            <span>{{ __('admin.save_changes') ?? 'حفظ التعديلات' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form 2: Change Password -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl font-black">
                        🔑
                    </span>
                    <div>
                        <h2 class="text-lg font-black text-slate-900">
                            {{ __('admin.change_password') ?? 'تغيير كلمة المرور' }}
                        </h2>
                        <p class="text-xs text-slate-500">
                            {{ __('admin.change_password_desc') ?? 'يجب إدخال كلمة المرور الحالية لتأكيد التغيير.' }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-black uppercase text-slate-700 mb-2">
                            {{ __('admin.current_password') ?? 'كلمة المرور الحالية' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="current_password" 
                               id="current_password" 
                               required
                               autocomplete="current-password"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 text-slate-900 text-sm font-semibold transition-all">
                        @error('current_password')
                            <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-black uppercase text-slate-700 mb-2">
                            {{ __('admin.new_password') ?? 'كلمة المرور الجديدة' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 text-slate-900 text-sm font-semibold transition-all">
                        <p class="text-xs text-slate-400 mt-1">{{ __('admin.password_hint') ?? '8 أحرف على الأقل.' }}</p>
                        @error('password')
                            <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-black uppercase text-slate-700 mb-2">
                            {{ __('admin.confirm_new_password') ?? 'تأكيد كلمة المرور الجديدة' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 text-slate-900 text-sm font-semibold transition-all">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-3 rounded-xl bg-gold-500 hover:bg-gold-400 text-slate-950 font-black text-sm transition-all shadow-md cursor-pointer flex items-center gap-2">
                            <span>🔐</span>
                            <span>{{ __('admin.update_password') ?? 'تحديث كلمة المرور' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
