<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(): View
    {
        return view('restaurants.index', [
            'restaurants' => Restaurant::latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('restaurants.create', [
            'restaurant' => new Restaurant(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $restaurant = Restaurant::create($this->validatedRestaurantData($request));

        return redirect()
            ->route('restaurants.show', $restaurant)
            ->with('success', 'Restaurant created.');
    }

    public function show(Restaurant $restaurant): View
    {
        $restaurant->loadCount('reservations');

        return view('restaurants.show', compact('restaurant'));
    }

    public function edit(Restaurant $restaurant): View
    {
        return view('restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant): RedirectResponse
    {
        $restaurant->update($this->validatedRestaurantData($request, $restaurant));

        return redirect()
            ->route('restaurants.show', $restaurant)
            ->with('success', 'Restaurant updated.');
    }

    public function destroy(Restaurant $restaurant): RedirectResponse
    {
        $restaurant->delete();

        return redirect()
            ->route('restaurants.index')
            ->with('success', 'Restaurant deleted. Existing reservations will keep their saved restaurant name.');
    }

    private function validatedRestaurantData(Request $request, ?Restaurant $restaurant = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('restaurants', 'name')->ignore($restaurant),
            ],
            'meta_pixel_id' => ['nullable', 'string', 'max:255'],
            'meta_access_token' => ['nullable', 'string'],
            'tiktok_pixel_id' => ['nullable', 'string', 'max:255'],
            'tiktok_access_token' => ['nullable', 'string'],
        ]);
    }
}