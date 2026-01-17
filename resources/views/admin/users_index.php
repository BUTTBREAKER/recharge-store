<?php
// User Management Index View
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-foreground">Gestión de Usuarios</h1>
        <p class="text-muted-foreground mt-1">Administra los usuarios registrados</p>
    </div>
    <a href="./admin/dashboard" class="bg-muted text-muted-foreground hover:bg-muted/80 px-5 py-2.5 rounded-xl font-bold transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Volver al Dashboard
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
<div class="bg-green-500/10 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <p class="text-green-500 font-medium">
            <?php
            switch ($_GET['success']) {
                case 'updated': echo 'Usuario actualizado correctamente'; break;
                case 'deleted': echo 'Usuario eliminado correctamente'; break;
                case 'role_changed': echo 'Rol cambiado correctamente'; break;
                default: echo 'Operación realizada correctamente';
            }
            ?>
        </p>
    </div>
</div>
<?php endif; ?>

<!-- Stats Cards -->
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-card rounded-xl shadow-sm border border-border p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-muted-foreground font-medium">Total Usuarios</p>
                <p class="text-2xl font-bold text-foreground"><?= $contadores['total'] ?? 0 ?></p>
            </div>
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
    </div>
    <div class="bg-card rounded-xl shadow-sm border border-border p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-muted-foreground font-medium">Administradores</p>
                <p class="text-2xl font-bold text-red-500"><?= $contadores['admin'] ?? 0 ?></p>
            </div>
            <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
        </div>
    </div>
    <div class="bg-card rounded-xl shadow-sm border border-border p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-muted-foreground font-medium">Usuarios Regulares</p>
                <p class="text-2xl font-bold text-blue-500"><?= $contadores['user'] ?? 0 ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<!-- Filtros -->
<div class="bg-card rounded-2xl shadow-sm border border-border p-4 mb-6">
    <div class="flex gap-3">
        <a href="./admin/users" class="px-4 py-2 rounded-lg text-sm font-bold transition <?= !$filtro ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground hover:bg-muted/80' ?>">
            Todos
        </a>
        <a href="./admin/users?rol=admin" class="px-4 py-2 rounded-lg text-sm font-bold transition <?= $filtro === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-muted text-muted-foreground hover:bg-muted/80' ?>">
            Administradores
        </a>
        <a href="./admin/users?rol=user" class="px-4 py-2 rounded-lg text-sm font-bold transition <?= $filtro === 'user' ? 'bg-blue-500/10 text-blue-500' : 'bg-muted text-muted-foreground hover:bg-muted/80' ?>">
            Usuarios
        </a>
    </div>
</div>

<!-- Tabla de Usuarios -->
<div class="bg-card rounded-2xl shadow-lg border border-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-muted/50 text-muted-foreground text-xs uppercase">
                <tr>
                    <th class="px-6 py-4 font-bold">ID</th>
                    <th class="px-6 py-4 font-bold">Usuario</th>
                    <th class="px-6 py-4 font-bold">Email</th>
                    <th class="px-6 py-4 font-bold">Rol</th>
                    <th class="px-6 py-4 font-bold">Registrado</th>
                    <th class="px-6 py-4 font-bold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-5xl mb-3 opacity-20">👥</div>
                        <p class="text-muted-foreground font-medium">No hay usuarios registrados</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($usuarios as $usuario): ?>
                <tr class="hover:bg-muted/50 transition">
                    <td class="px-6 py-4 font-mono text-sm text-muted-foreground">#<?= $usuario['id'] ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br <?= $usuario['role'] === 'admin' ? 'from-red-500 to-orange-600' : 'from-blue-500 to-cyan-600' ?> rounded-full flex items-center justify-center text-white font-bold mr-3">
                                <?= strtoupper(substr($usuario['name'], 0, 2)) ?>
                            </div>
                            <div class="font-bold text-foreground"><?= htmlspecialchars($usuario['name']) ?></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-muted-foreground"><?= htmlspecialchars($usuario['email']) ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $usuario['role'] === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-blue-500/10 text-blue-500' ?>">
                            <?= $usuario['role'] === 'admin' ? '🛡️ Admin' : '👤 Usuario' ?>
                        </span>
                    <td class="px-6 py-4 text-muted-foreground text-sm">
                        <?= date('d/m/Y', strtotime($usuario['created_at'])) ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="./admin/users/edit/<?= $usuario['id'] ?>" class="text-muted-foreground hover:text-primary transition p-2" title="Gestionar Cuenta">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </a>
                            <?php if ($usuario['role'] === 'user'): ?>
                            <form action="./admin/users/make-admin" method="POST" class="inline" onsubmit="return confirm('¿Convertir a este usuario en administrador?');">
                                <?php csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                <button type="submit" class="text-muted-foreground hover:text-red-500 transition p-2" title="Hacer Admin">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </button>
                            </form>
                            <?php else: ?>
                            <form action="./admin/users/make-user" method="POST" class="inline" onsubmit="return confirm('¿Quitar permisos de administrador a este usuario?');">
                                <?php csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                <button type="submit" class="text-muted-foreground hover:text-blue-500 transition p-2" title="Hacer Usuario">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Info -->
<div class="mt-6 bg-yellow-500/10 border-l-4 border-yellow-500 p-4 rounded-r-xl">
    <div class="flex items-start">
        <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        <div class="text-sm text-yellow-500">
            <p class="font-bold mb-1">Precaución</p>
            <p>Los cambios de rol afectan los permisos inmediatamente. Asegúrate de mantener al menos un administrador activo.</p>
        </div>
    </div>
</div>
