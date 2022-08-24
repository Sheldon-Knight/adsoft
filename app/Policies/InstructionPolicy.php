<?php

namespace App\Policies;

use App\Models\Instruction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstructionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view any instructions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instruction  $instruction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Instruction $instruction)
    {
        if ($instruction->deleted_at != null) {
            return false;
        }
        
        return $user->can('view instructions');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create instructions');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instruction  $instruction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Instruction $instruction)
    {
        if ($instruction->deleted_at != null) {
            return false;
        }
        return $user->can('update instructions');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instruction  $instruction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Instruction $instruction)
    {

        if ($instruction->deleted_at != null) {
            return false;
        }

        return $user->can('delete instructions');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instruction  $instruction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Instruction $instruction)
    {
        if ($instruction->deleted_at === null) {
            return false;
        }

        return $user->can('restore instructions');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Instruction  $instruction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Instruction $instruction)
    {
        if ($instruction->deleted_at === null) {
            return false;
        }

        return $user->can('force delete instructions');
    }
}
