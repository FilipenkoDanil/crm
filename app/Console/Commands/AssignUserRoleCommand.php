<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignUserRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:role {userId} {roleName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role to user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        User::findOrFail($this->argument('userId'))->syncRoles($this->argument('roleName'));
        $this->info('Role assigned to user');

        return 1;
    }
}
