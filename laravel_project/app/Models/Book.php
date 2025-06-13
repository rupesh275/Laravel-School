<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';
    protected $fillable = ['book_title', 'book_no', 'isbn_no', 'subject', 'rack_no', 'publish', 'author', 'qty', 'purchase_cost', 'postdate', 'description', 'available', 'excession_no', 'call_no', 'barcode', 'place_of_publication', 'date_of_publication', 'no_of_page', 'category', 'price', 'classification_no', 'extent', 'physical_details', 'item_type', 'is_active', 'status'];
}
