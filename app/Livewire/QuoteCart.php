<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class QuoteCart extends Component
{
    public array $cart = [];
    public bool $isOpen = false;

    public function mount(): void
    {
        $this->cart = session()->get('quote_cart', []);
        $this->dispatch('cart-updated', count: $this->getItemCount());
    }

    #[On('toggle-quote-cart')]
    public function toggleCart(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    #[On('add-to-cart')]
    public function addItem(int $productId, string $packagingType, string $size, float $quantity = 1, string $unit = 'units', bool $isPrivateLabel = false): void
    {
        $product = Product::with('category')->find($productId);
        if (!$product) {
            return;
        }

        $itemKey = md5($productId . '_' . $packagingType . '_' . $size . '_' . ($isPrivateLabel ? '1' : '0'));

        if (isset($this->cart[$itemKey])) {
            $this->cart[$itemKey]['quantity'] += $quantity;
        } else {
            $this->cart[$itemKey] = [
                'key' => $itemKey,
                'product_id' => $productId,
                'product_title' => $product->localized_title,
                'category_name' => $product->category->localized_name ?? '',
                'image' => $product->featured_image,
                'packaging_type' => $packagingType,
                'size' => $size,
                'quantity' => $quantity,
                'unit' => $unit,
                'is_private_label' => $isPrivateLabel,
            ];
        }

        session()->put('quote_cart', $this->cart);
        $this->dispatch('cart-updated', count: $this->getItemCount());
        $this->isOpen = true; // Ouvrir le tiroir automatiquement à l'ajout
    }

    public function removeItem(string $key): void
    {
        unset($this->cart[$key]);
        session()->put('quote_cart', $this->cart);
        $this->dispatch('cart-updated', count: $this->getItemCount());
    }

    public function updateQuantity(string $key, float $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($key);
            return;
        }

        if (isset($this->cart[$key])) {
            $this->cart[$key]['quantity'] = $quantity;
            session()->put('quote_cart', $this->cart);
            $this->dispatch('cart-updated', count: $this->getItemCount());
        }
    }

    public function clearCart(): void
    {
        $this->cart = [];
        session()->forget('quote_cart');
        $this->dispatch('cart-updated', count: 0);
    }

    public function getItemCount(): int
    {
        return count($this->cart);
    }

    public function render()
    {
        return view('livewire.quote-cart');
    }
}
