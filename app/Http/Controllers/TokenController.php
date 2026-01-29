<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TokenController extends Controller
{
    public const TOKEN_ABILITIES = [
        'gallery-read' => 'Gallery - Read Only (View galleries and images)',
        'gallery-manage' => 'Gallery - Full Access (Create, edit, delete galleries)',
        'user-read' => 'User - Read Profile',
        'full-access' => 'Full API Access (All permissions)',
    ];

    public function index(): \Illuminate\Contracts\View\View
    {
        $tokens = auth()->user()->tokens()->orderBy('created_at', 'desc')->get();

        return view('tokens.index', compact('tokens'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'required|array|min:1',
            'abilities.*' => 'in:gallery-read,gallery-manage,user-read,full-access',
        ]);

        $abilities = $request->abilities;

        // If full-access is selected, grant all abilities
        if (in_array('full-access', $abilities)) {
            $abilities = ['*'];
        }

        $token = auth()->user()->createToken(
            $request->name,
            $abilities
        );

        return redirect()->route('tokens.index')
            ->with('token', $token->plainTextToken)
            ->with('success', 'API token created successfully! Make sure to copy it now - you won\'t be able to see it again.');
    }

    public function destroy(string $tokenId): \Illuminate\Http\RedirectResponse
    {
        auth()->user()->tokens()->where('id', $tokenId)->delete();

        return redirect()->route('tokens.index')
            ->with('success', 'API token deleted successfully.');
    }
}
