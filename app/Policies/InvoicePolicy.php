<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
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
        if ($user->is_admin == true) {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('view any invoices');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Invoice $invoice)
    {
        if ($user->is_admin == true) {
            return false;
        }
        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if ($invoice->deleted_at != null) {
            return false;
        }

        return $user->can('view invoices');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->is_admin == true) {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('create invoices');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Invoice $invoice)
    {
        if ($user->is_admin == true) {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if ($invoice->deleted_at != null) {
            return false;
        }

        return $user->can('update invoices');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Invoice $invoice)
    {
        if ($user->is_admin == true) {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }

        return $user->can('delete invoices');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Invoice $invoice)
    {
        if ($user->is_admin == true) {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if ($invoice->deleted_at === null) {
            return false;
        }

        return $user->can('restore invoices');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Invoice $invoice)
    {
        if ($user->is_admin == true) {
            return false;
        }

        if (cache()->get('hasExpired') == true) {
            return false;
        }

        if ($invoice->deleted_at === null) {
            return false;
        }

        return $user->can('force delete invoices');
    }
}
