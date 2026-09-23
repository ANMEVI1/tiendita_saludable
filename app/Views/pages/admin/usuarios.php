<?php require __DIR__ . '/../../layouts/admin_header.php'; ?>

<div x-data="adminUsuarios()">
    <div class="mb-8 flex justify-between items-end flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-heading font-bold text-white tracking-wide uppercase mb-2">Usuarios del Sistema</h1>
            <p class="text-gray-400 text-sm font-light">Gestión de accesos, roles y permisos (RBAC).</p>
        </div>
        <button @click="openNewModal()" class="bg-gold text-black font-bold text-sm tracking-widest uppercase px-6 py-3 rounded-lg hover:bg-white hover:scale-105 transition-all shadow-[0_0_15px_rgba(212,175,55,0.3)] flex items-center gap-2">
            <i class="ph-bold ph-user-plus"></i> Nuevo Usuario
        </button>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="bg-[#111] border border-[#222] rounded-xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#222]">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Usuario / Email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Rol de Acceso</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#222]">
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No hay usuarios registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $usr): ?>
                            <tr class="hover:bg-[#151515] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-[#1a1a1a] border border-[#333] flex items-center justify-center text-gold font-bold text-sm">
                                            <?= strtoupper(substr($usr['email'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-white"><?= htmlspecialchars($usr['email']) ?></p>
                                            <p class="text-[10px] text-gray-500 font-light">ID: #<?= $usr['id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($usr['rol_id'] == 1): ?>
                                        <span class="px-3 py-1 bg-gold/10 text-gold border border-gold/30 text-[10px] font-bold uppercase tracking-widest rounded-full">Administrador</span>
                                    <?php elseif ($usr['rol_id'] == 2): ?>
                                        <span class="px-3 py-1 bg-blue-900/30 text-blue-400 border border-blue-500/30 text-[10px] font-bold uppercase tracking-widest rounded-full">Vendedor</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-gray-800 text-gray-300 border border-gray-700 text-[10px] font-bold uppercase tracking-widest rounded-full">Cliente</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($usr['activo']): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-900/30 text-green-400 border border-green-500/30 text-[10px] font-semibold uppercase tracking-wider rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span> Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-900/30 text-red-400 border border-red-500/30 text-[10px] font-semibold uppercase tracking-wider rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click='openEditModal(<?= htmlspecialchars(json_encode($usr, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, "UTF-8") ?>)' class="text-gray-400 hover:text-gold transition-colors p-1" title="Editar Usuario">
                                        <i class="ph ph-pencil-simple text-lg"></i>
                                    </button>
                                    <?php if ($usr['id'] != $_SESSION['user_id']): ?>
                                        <button @click='openDeleteModal(<?= htmlspecialchars(json_encode($usr, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, "UTF-8") ?>)' class="text-gray-400 hover:text-red-500 transition-colors p-1 ml-2" title="Eliminar Usuario">
                                            <i class="ph ph-trash text-lg"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nuevo Usuario -->
    <div x-show="modalNewOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalNewOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-lg relative shadow-2xl rounded-xl">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <button type="button" @click="modalNewOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                <i class="ph ph-x text-xl"></i>
            </button>
            
            <div class="mb-6">
                <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Nuevo Usuario</h2>
                <p class="text-[#666] text-xs font-light">Crea una cuenta de acceso al sistema.</p>
            </div>
            
            <form action="/admin/usuarios/store" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Correo Electrónico *</label>
                    <input type="email" name="email" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" placeholder="usuario@tiendita.com" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Contraseña *</label>
                    <input type="password" name="password" minlength="6" maxlength="100" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" placeholder="******" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Rol de Acceso *</label>
                    <select name="rol_id" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg" required>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="w-full bg-gold text-black py-3 text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors rounded-lg shadow-lg">
                    Crear Usuario
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div x-show="modalEditOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalEditOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-lg relative shadow-2xl rounded-xl">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <button type="button" @click="modalEditOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                <i class="ph ph-x text-xl"></i>
            </button>
            
            <div class="mb-6">
                <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Editar Usuario</h2>
                <p class="text-[#666] text-xs font-light">Modifica los permisos o restablece la contraseña.</p>
            </div>
            
            <form action="/admin/usuarios/update" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" x-model="form.id">
                
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Correo Electrónico *</label>
                    <input type="email" name="email" x-model="form.email" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" required>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Rol de Acceso *</label>
                    <select name="rol_id" x-model="form.rol_id" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg" required>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Nueva Contraseña (Opcional)</label>
                    <input type="password" name="password" placeholder="Dejar en blanco para mantener la actual" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg">
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="activo_edit" name="activo" value="1" x-model="form.activo" class="w-4 h-4 rounded border-[#333] bg-[#111] text-gold focus:ring-gold accent-gold">
                    <label for="activo_edit" class="text-xs text-gray-300 font-medium cursor-pointer">Usuario Activo</label>
                </div>

                <button type="submit" class="w-full bg-gold text-black py-3 text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors rounded-lg shadow-lg">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Eliminar Usuario -->
    <div x-show="modalDeleteOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalDeleteOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-md relative shadow-2xl rounded-xl text-center">
            <div class="w-16 h-16 bg-red-900/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-500/30">
                <i class="ph ph-trash text-3xl"></i>
            </div>
            
            <h3 class="text-lg font-heading font-bold text-white uppercase tracking-wider mb-2">Confirmar Eliminación</h3>
            <p class="text-gray-400 text-xs mb-6">¿Estás seguro de que deseas eliminar la cuenta <strong class="text-white" x-text="formDelete.email"></strong>?</p>
            
            <form action="/admin/usuarios/delete" method="POST" class="flex gap-3">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" x-model="formDelete.id">
                
                <button type="button" @click="modalDeleteOpen = false" class="w-1/2 bg-[#222] text-gray-300 py-2.5 text-xs font-bold uppercase tracking-widest hover:bg-[#333] transition-colors rounded-lg">
                    Cancelar
                </button>
                <button type="submit" class="w-1/2 bg-red-600 text-white py-2.5 text-xs font-bold uppercase tracking-widest hover:bg-red-700 transition-colors rounded-lg shadow-lg">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function adminUsuarios() {
        return {
            modalNewOpen: false,
            modalEditOpen: false,
            modalDeleteOpen: false,
            form: {
                id: '',
                email: '',
                rol_id: '',
                activo: true
            },
            formDelete: {
                id: '',
                email: ''
            },
            openNewModal() {
                this.modalNewOpen = true;
            },
            openEditModal(usr) {
                this.form.id = usr.id;
                this.form.email = usr.email;
                this.form.rol_id = usr.rol_id;
                this.form.activo = Boolean(Number(usr.activo));
                this.modalEditOpen = true;
            },
            openDeleteModal(usr) {
                this.formDelete.id = usr.id;
                this.formDelete.email = usr.email;
                this.modalDeleteOpen = true;
            }
        }
    }
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
