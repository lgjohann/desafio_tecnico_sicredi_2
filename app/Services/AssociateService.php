<?php

namespace App\Services;

use App\Exceptions\NotFoundException\AssociateNotFoundException;
use App\Models\Associate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;

class AssociateService
{
    /**
     * Creates a new associate.
     *
     * @param array{name: string, email:string, cpf: string, telephone: string, city: string, state: string} $data validated Associate creation data.
     * @return Associate
     *
     * @throws QueryException if there is a database persistence error.
     */
    public function create(array $data): Associate
    {
        $associate = Associate::create($data);

        Log::info('New Associate registered', [
            'associate_id' => $associate->id,
            'name'        => $associate->name,
            'email'   => $associate->email,
            'cpf'   => $associate->cpf,
            'telephone'   => $associate->telephone,
            'city'   => $associate->city,
            'state'   => $associate->state,
            'ip'      => request()->ip()
        ]);

        return $associate;
    }

    /**
     * Associate paginated list.
     *
     * @param int $perPage the number of desired items per page
     * @return LengthAwarePaginator - default laravel pagination object
     */
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Associate::paginate($perPage);
    }

    /**
     * Updates an existing Associate.
     *
     * @param int $id
     * @param array{name: string, email:string, cpf: string, telephone: string, city: string, state: string} $data validated Associate update data.
     * @return Associate
     *
     * @throws AssociateNotFoundException if the Associate with the given id is not found.
     */
    public function update(int $id, array $data): Associate
    {
        try {
            $associate = Associate::findOrFail($id);
            $associate->update($data);

            return $associate;
        } catch (ModelNotFoundException $e) {
            Log::error("Associate with id {$id} not found");
            throw new AssociateNotFoundException("Associate with id {$id} not found");
        }

    }

    /**
     * Deletes an Associate.
     *
     * @throws AssociateNotFoundException if the Associate with the given id is not found.
     */
    public function delete(int $id): void
    {
        try {
            $associate = Associate::findOrFail($id);
            $associate->delete();
        } catch (ModelNotFoundException $e) {
            Log::error("Associate with id {$id} not found");
            throw new AssociateNotFoundException("Associate with id {$id} not found");
        }

    }
}
