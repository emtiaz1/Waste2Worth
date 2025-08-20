<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c3338;
            color: #ffffff;
        }

        .card {
            background-color: #1a1d20;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .table {
            color: #ffffff;
        }

        .table thead th {
            background-color: #3a4147;
            color: #ffffff;
            border-color: #4a5157;
        }

        .table td {
            border-color: #4a5157;
            vertical-align: middle;
        }

        .btn-action {
            padding: 5px 10px;
            margin: 0 2px;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    @include('admin.adminsidebar')

    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Products Management</h2>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus me-2"></i>Add New Product
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-3 position-relative" style="max-width: 350px;">
                <span class="position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; z-index: 2;">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="productSearch" class="form-control bg-secondary text-white ps-5" placeholder="Search products..." style="padding-left: 2.2rem;">
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="productsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Stock</th>
                                    <th>Eco Coins</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="product-row">
                                        <td class="product-id">{{ $product['id'] }}</td>
                                        <td>
                                            <img src="{{ asset($product['image']) }}"
                                                alt="{{ $product['name'] }}" class="product-image">
                                        </td>
                                        <td class="product-name">{{ $product['name'] }}</td>
                                        <td class="product-stock">{{ $product['stock'] }}</td>
                                        <td class="product-eco">{{ $product['eco_coin_value'] }}</td>
                                        <td class="product-desc">{{ Str::limit($product['description'], 50) }}</td>
                                        <td>{{ $product['created_at'] }}</td>
                                        <td>{{ $product['updated_at'] }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-action"
                                                onclick="editProduct({{ json_encode($product) }})" data-bs-toggle="modal"
                                                data-bs-target="#editProductModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.products') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="{{ $product['id'] }}">
                                                <button type="submit" class="btn btn-danger btn-action"
                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.products') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="add">

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control bg-secondary text-white" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control bg-secondary text-white" name="image" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" class="form-control bg-secondary text-white" name="stock" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Eco Coin Value</label>
                            <input type="number" class="form-control bg-secondary text-white" name="eco_coin_value"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control bg-secondary text-white" name="description" rows="3"
                                required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.products') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control bg-secondary text-white" name="name" id="edit_name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control bg-secondary text-white" name="image">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" class="form-control bg-secondary text-white" name="stock"
                                id="edit_stock" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Eco Coin Value</label>
                            <input type="number" class="form-control bg-secondary text-white" name="eco_coin_value"
                                id="edit_eco_coin_value" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control bg-secondary text-white" name="description"
                                id="edit_description" rows="3" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editProduct(product) {
            document.getElementById('edit_id').value = product.id;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_stock').value = product.stock;
            document.getElementById('edit_eco_coin_value').value = product.eco_coin_value;
            document.getElementById('edit_description').value = product.description;
        }
        // Product search functionality
        document.getElementById('productSearch').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            document.querySelectorAll('.product-row').forEach(function(row) {
                const name = row.querySelector('.product-name').textContent.toLowerCase();
                const desc = row.querySelector('.product-desc').textContent.toLowerCase();
                const eco = row.querySelector('.product-eco').textContent.toLowerCase();
                const stock = row.querySelector('.product-stock').textContent.toLowerCase();
                if (
                    name.includes(search) ||
                    desc.includes(search) ||
                    eco.includes(search) ||
                    stock.includes(search)
                ) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>