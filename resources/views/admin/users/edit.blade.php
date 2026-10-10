<x-layouts.admin>
    <div class="space-y-8 max-w-3xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border border-gold-500/20 rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="w-10 h-10 rounded-2xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-lg transition-all">
                    ←
                </a>
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-gold-500/20 text-gold-300 border border-gold-400/30 mb-1">
                        👥 {{ __('admin.access_control_rbac') ?? 'تعديل المشرف' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-white">
                        {{ __('admin.edit_admin') ?? 'تعديل حساب المشرف' }}: {{ $user->name }}
                    </h1>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
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

                <div>
                    <label for="password" class="block text-xs font-black uppercase text-slate-700 mb-2">
                        {{ __('admin.new_password') ?? 'تعيين كلمة مرور جديدة (اختياري)' }}
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           placeholder="اترك هذا الحقل فارغاً إذا كنت لا ترغب في تغييرها"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 text-slate-900 text-sm font-semibold transition-all">
                    <p class="text-xs text-slate-400 mt-1">اتركه فارغاً للاحتفاظ بكلمة المرور الحالية.</p>
                    @error('password')
                        <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="space-y-3 pt-2">
                    <label class="block text-xs font-black uppercase text-slate-700 mb-2">
                        {{ __('admin.assigned_role') ?? 'تحديد الدور والصلاحيات' }} <span class="text-red-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $currentRole = old('role', $user->roles->first()?->name ?? 'admin');
                        @endphp

                        <!-- Standard Admin -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-gold-400 bg-slate-50/50 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/20 has-[:checked]:shadow-md">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-black text-sm text-slate-900 flex items-center gap-1.5">
                                    <span>💼</span>
                                    <span>مشرف مبيعات وتصدير (Admin)</span>
                                </span>
                                <input type="radio" name="role" value="admin" {{ $currentRole === 'admin' ? 'checked' : '' }} class="w-4 h-4 text-gold-500 focus:ring-gold-400">
                            </div>
                            <span class="text-xs text-slate-500 leading-relaxed">
                                صلاحيات محدودة: إدارة الصفقات وتسعيرها، تعديل المنتجات والواجهة، بدون حق حذف الصفقات أو المنتجات نهائياً.
                            </span>
                        </label>

                        <!-- Super Admin -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-gold-400 bg-slate-50/50 has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50/20 has-[:checked]:shadow-md">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-black text-sm text-slate-900 flex items-center gap-1.5">
                                    <span>👑</span>
                                    <span>مدير عام (Super Admin)</span>
                                </span>
                                <input type="radio" name="role" value="super_admin" {{ $currentRole === 'super_admin' ? 'checked' : '' }} class="w-4 h-4 text-gold-500 focus:ring-gold-400">
                            </div>
                            <span class="text-xs text-slate-500 leading-relaxed">
                                صلاحيات كاملة: حذف الصفقات والمنتجات نهائياً، إدارة وإضافة المشرفين، والتحكم الكامل في المنصة.
                            </span>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-bold transition-all">
                        {{ __('admin.cancel') ?? 'إلغاء' }}
                    </a>

                    <button type="submit" class="px-6 py-3 rounded-xl bg-gold-500 hover:bg-gold-400 text-slate-950 font-black text-sm transition-all shadow-md cursor-pointer flex items-center gap-2">
                        <span>💾</span>
                        <span>{{ __('admin.save_changes') ?? 'حفظ التعديلات' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
