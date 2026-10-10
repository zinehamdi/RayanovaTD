<x-layouts.admin>
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border border-gold-500/20 rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-gold-500/20 text-gold-300 border border-gold-400/30 mb-2">
                        👥 {{ __('admin.access_control_rbac') ?? 'إدارة الصلاحيات والمشرفين (RBAC)' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-white">
                        {{ __('admin.team_administrators') ?? 'فريق العمل والمسؤولين' }}
                    </h1>
                    <p class="text-sm text-slate-300 mt-1">
                        {{ __('admin.team_administrators_desc') ?? 'إدارة حسابات المشرفين وتحديد الصلاحيات بين المدير العام ومسؤولي المبيعات.' }}
                    </p>
                </div>

                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-300 text-slate-950 font-black text-sm transition-all shadow-lg shadow-gold-500/20 cursor-pointer">
                    <span>➕</span>
                    <span>{{ __('admin.add_new_admin') ?? 'إضافة مشرف جديد' }}</span>
                </a>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-black text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6 text-start">{{ __('admin.administrator') ?? 'المشرف' }}</th>
                            <th class="py-4 px-6 text-start">{{ __('admin.role_permissions') ?? 'الدور والصلاحيات' }}</th>
                            <th class="py-4 px-6 text-start">{{ __('admin.created_at') ?? 'تاريخ الإنشاء' }}</th>
                            <th class="py-4 px-6 text-center">{{ __('admin.actions') ?? 'الإجراءات' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Name & Email -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $u->hasRole('super_admin') ? 'from-gold-400 to-gold-600' : 'from-slate-700 to-slate-900' }} text-white font-black text-sm flex items-center justify-center shadow-sm shrink-0">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 flex items-center gap-2">
                                                <span>{{ $u->name }}</span>
                                                @if($u->id === auth()->id())
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-700">
                                                        {{ __('admin.you') ?? 'أنت' }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-slate-500 font-medium">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Role & Permissions Badge -->
                                <td class="py-4 px-6">
                                    @if($u->hasRole('super_admin'))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-gold-100 text-gold-800 border border-gold-300">
                                            <span>👑</span>
                                            <span>{{ __('admin.super_admin_role') ?? 'المدير العام (كامل الصلاحيات)' }}</span>
                                        </span>
                                        <p class="text-[11px] text-slate-400 mt-1">حذف المنتجات، حذف الصفقات، إدارة المشرفين</p>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <span>💼</span>
                                            <span>{{ __('admin.sales_officer_role') ?? 'مشرف المبيعات (صلاحيات محدودة)' }}</span>
                                        </span>
                                        <p class="text-[11px] text-slate-400 mt-1">تسعير الصفقات، إدارة الكتالوج (بدون حذف نهائي)</p>
                                    @endif
                                </td>

                                <!-- Created At -->
                                <td class="py-4 px-6 text-xs text-slate-500 font-semibold">
                                    {{ $u->created_at ? $u->created_at->format('Y-m-d H:i') : '-' }}
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.edit', $u->id) }}" 
                                           class="p-2 rounded-xl bg-slate-100 hover:bg-gold-50 text-slate-700 hover:text-gold-700 border border-slate-200 transition-all font-bold text-xs"
                                           title="{{ __('admin.edit') ?? 'تعديل' }}">
                                            ✏️ {{ __('admin.edit') ?? 'تعديل' }}
                                        </a>

                                        @if($u->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $u->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('{{ __('admin.confirm_delete_admin') ?? 'هل أنت متأكد من حذف هذا الحساب؟' }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 transition-all font-bold text-xs cursor-pointer"
                                                        title="{{ __('admin.delete') ?? 'حذف' }}">
                                                    🗑️ {{ __('admin.delete') ?? 'حذف' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
