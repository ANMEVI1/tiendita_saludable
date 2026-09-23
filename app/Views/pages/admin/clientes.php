<?php require __DIR__ . '/../../layouts/admin_header.php'; ?>

<div x-data="adminClientes()">
    <div class="mb-8 flex justify-between items-end flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-heading font-bold text-white tracking-wide uppercase mb-2">Clientes y Perfiles</h1>
            <p class="text-gray-400 text-sm font-light">Gestión de datos de envío, documentos y contacto de clientes.</p>
        </div>
        <button @click="openNewModal()" class="bg-gold text-black font-bold text-sm tracking-widest uppercase px-6 py-3 rounded-lg hover:bg-white hover:scale-105 transition-all shadow-[0_0_15px_rgba(212,175,55,0.3)] flex items-center gap-2">
            <i class="ph-bold ph-user-focus"></i> Registrar Cliente
        </button>
    </div>

    <!-- Tabla de Clientes -->
    <div class="bg-[#111] border border-[#222] rounded-xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#222]">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Cliente / Nombres</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Cuenta / Email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Documento</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">WhatsApp</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a]">Dirección</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest bg-[#0a0a0a] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#222]">
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">No hay clientes registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cli): ?>
                            <tr class="hover:bg-[#151515] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-[#1a1a1a] border border-[#333] flex items-center justify-center text-gold font-bold text-sm">
                                            <?= strtoupper(substr($cli['nombres'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-white"><?= htmlspecialchars($cli['nombres'] . ' ' . $cli['apellidos']) ?></p>
                                            <p class="text-[10px] text-gray-500 font-light">ID Cliente: #<?= $cli['id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-gray-300"><?= htmlspecialchars($cli['usuario_email']) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-gray-300">
                                        <?= htmlspecialchars(($cli['tipo_documento'] ?? 'DNI') . ': ' . ($cli['nro_documento'] ?? 'N/A')) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($cli['telefono_whatsapp'])): ?>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $cli['telefono_whatsapp']) ?>" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-green-400 hover:text-green-300">
                                            <i class="ph-bold ph-whatsapp-logo text-base"></i> <?= htmlspecialchars($cli['telefono_whatsapp']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-600">No registrado</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-gray-400 block max-w-xs truncate"><?= htmlspecialchars($cli['direccion'] ?? 'Sin dirección') ?></span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click='openEditModal(<?= htmlspecialchars(json_encode($cli, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, "UTF-8") ?>)' class="text-gray-400 hover:text-gold transition-colors p-1" title="Editar Cliente">
                                        <i class="ph ph-pencil-simple text-lg"></i>
                                    </button>
                                    <button @click='openDeleteModal(<?= htmlspecialchars(json_encode($cli, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, "UTF-8") ?>)' class="text-gray-400 hover:text-red-500 transition-colors p-1 ml-2" title="Eliminar Cliente">
                                        <i class="ph ph-trash text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nuevo Cliente -->
    <div x-show="modalNewOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalNewOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-lg relative shadow-2xl rounded-xl max-h-[90vh] overflow-y-auto">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <button type="button" @click="modalNewOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                <i class="ph ph-x text-xl"></i>
            </button>
            
            <div class="mb-6">
                <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Registrar Cliente</h2>
                <p class="text-[#666] text-xs font-light">Asocia un perfil de cliente a un usuario registrado.</p>
            </div>
            
            <form action="/admin/clientes/store" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                
                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Seleccionar Usuario de Cuenta *</label>
                    <select name="usuario_id" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg" required>
                        <option value="">-- Seleccionar Usuario sin perfil --</option>
                        <?php foreach ($usuariosDisponibles as $uDisp): ?>
                            <option value="<?= $uDisp['id'] ?>"><?= htmlspecialchars($uDisp['email']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Nombres *</label>
                        <input type="text" name="nombres" maxlength="100" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" placeholder="Juan" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Apellidos *</label>
                        <input type="text" name="apellidos" maxlength="100" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" placeholder="Pérez" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Tipo Documento</label>
                        <select name="tipo_documento" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg">
                            <option value="DNI">DNI</option>
                            <option value="CE">Carnét de Extranjería (CE)</option>
                            <option value="Pasaporte">Pasaporte</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">N° Documento</label>
                        <input type="text" name="nro_documento" maxlength="20" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" placeholder="71234567">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Teléfono / WhatsApp</label>
                    <input type="text" name="telefono_whatsapp" maxlength="20" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" placeholder="51984247684">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Dirección de Entrega</label>
                    <textarea name="direccion" rows="2" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg resize-none" placeholder="AA.HH. Andrés Araujo Morán Mz A Lt 5"></textarea>
                </div>

                <button type="submit" class="w-full bg-gold text-black py-3 text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors rounded-lg shadow-lg">
                    Guardar Perfil
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Editar Cliente -->
    <div x-show="modalEditOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalEditOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-lg relative shadow-2xl rounded-xl max-h-[90vh] overflow-y-auto">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold/40 to-transparent"></div>
            
            <button type="button" @click="modalEditOpen = false" class="absolute top-4 right-4 text-[#555] hover:text-gold transition-colors focus:outline-none">
                <i class="ph ph-x text-xl"></i>
            </button>
            
            <div class="mb-6">
                <h2 class="text-[1.35rem] font-heading font-bold text-white tracking-widest uppercase mb-1">Editar Cliente</h2>
                <p class="text-[#666] text-xs font-light">Actualiza los datos personales y de entrega.</p>
            </div>
            
            <form action="/admin/clientes/update" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="id" x-model="form.id">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Nombres *</label>
                        <input type="text" name="nombres" x-model="form.nombres" maxlength="100" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Apellidos *</label>
                        <input type="text" name="apellidos" x-model="form.apellidos" maxlength="100" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Tipo Documento</label>
                        <select name="tipo_documento" x-model="form.tipo_documento" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg">
                            <option value="DNI">DNI</option>
                            <option value="CE">Carnét de Extranjería (CE)</option>
                            <option value="Pasaporte">Pasaporte</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">N° Documento</label>
                        <input type="text" name="nro_documento" x-model="form.nro_documento" maxlength="20" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Teléfono / WhatsApp</label>
                    <input type="text" name="telefono_whatsapp" x-model="form.telefono_whatsapp" maxlength="20" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-white focus:border-gold outline-none transition-colors rounded-lg">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Dirección de Entrega</label>
                    <textarea name="direccion" x-model="form.direccion" rows="2" class="w-full bg-[#111] border border-[#222] p-3 text-sm text-gray-300 focus:border-gold outline-none transition-colors rounded-lg resize-none"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="activo_cli_edit" name="activo" value="1" x-model="form.activo" class="w-4 h-4 rounded border-[#333] bg-[#111] text-gold focus:ring-gold accent-gold">
                    <label for="activo_cli_edit" class="text-xs text-gray-300 font-medium cursor-pointer">Cliente Activo</label>
                </div>

                <button type="submit" class="w-full bg-gold text-black py-3 text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors rounded-lg shadow-lg">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Eliminar Cliente -->
    <div x-show="modalDeleteOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80" style="display: none;" x-transition.opacity>
        <div @click.away="modalDeleteOpen = false" class="bg-[#0c0c0c] border border-[#1a1a1a] p-8 w-full max-w-md relative shadow-2xl rounded-xl text-center">
            <div class="w-16 h-16 bg-red-900/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-500/30">
                <i class="ph ph-trash text-3xl"></i>
            </div>
            
            <h3 class="text-lg font-heading font-bold text-white uppercase tracking-wider mb-2">Confirmar Eliminación</h3>
            <p class="text-gray-400 text-xs mb-6">¿Estás seguro de que deseas eliminar el perfil de cliente de <strong class="text-white" x-text="formDelete.nombres"></strong>?</p>
            
            <form action="/admin/clientes/delete" method="POST" class="flex gap-3">
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
    function adminClientes() {
        return {
            modalNewOpen: false,
            modalEditOpen: false,
            modalDeleteOpen: false,
            form: {
                id: '',
                nombres: '',
                apellidos: '',
                tipo_documento: 'DNI',
                nro_documento: '',
                telefono_whatsapp: '',
                direccion: '',
                activo: true
            },
            formDelete: {
                id: '',
                nombres: ''
            },
            openNewModal() {
                this.modalNewOpen = true;
            },
            openEditModal(cli) {
                this.form.id = cli.id;
                this.form.nombres = cli.nombres || '';
                this.form.apellidos = cli.apellidos || '';
                this.form.tipo_documento = cli.tipo_documento || 'DNI';
                this.form.nro_documento = cli.nro_documento || '';
                this.form.telefono_whatsapp = cli.telefono_whatsapp || '';
                this.form.direccion = cli.direccion || '';
                this.form.activo = Boolean(Number(cli.activo));
                this.modalEditOpen = true;
            },
            openDeleteModal(cli) {
                this.formDelete.id = cli.id;
                this.formDelete.nombres = (cli.nombres + ' ' + cli.apellidos);
                this.modalDeleteOpen = true;
            }
        }
    }
</script>

<?php require __DIR__ . '/../../layouts/admin_footer.php'; ?>
