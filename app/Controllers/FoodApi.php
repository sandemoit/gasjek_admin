<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\FoodModel;
use CodeIgniter\RESTful\ResourceController;

class FoodApi extends ResourceController
{
    use ResponseTrait;

    protected $foodModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->foodModel = new FoodModel();
    }

    public function index()
    {
        // Mengambil parameter dari request
        $id_restaurant = $this->request->getVar('id_restaurant');
        $id_food = $this->request->getVar('id_food');

        // Inisialisasi variabel model dengan seluruh data makanan
        $model = $this->foodModel->findAll();

        // Memeriksa apakah parameter id_restaurant atau id_food tidak null
        if ($id_restaurant != null) {
            // Jika id_restaurant tidak null, ambil data makanan berdasarkan id_restaurant
            $model = $this->foodModel->getFood($id_restaurant);
        } elseif ($id_food != null) {
            // Jika id_food tidak null, ambil data makanan berdasarkan id_food
            $model = [$this->foodModel->getFoodId($id_food)];
        }

        // Menyiapkan respons
        $response = [
            'status' => 200,
            'message' => 'success',
            'dataFood' => $model
        ];

        // Mengembalikan respons
        return $this->respond($response);
    }

    public function create()
    {
        try {
            // Mendapatkan data dari request
            $id_restaurant = $this->request->getPost('id_restaurant');
            $food_name = $this->request->getPost('food_name');
            $food_price = $this->request->getPost('food_price');
            $food_quantity = $this->request->getPost('food_quantity');
            $food_image = $this->request->getPost('food_image');
            $food_desc = $this->request->getPost('food_desc');

            // Validasi input gambar
            if (empty($food_image)) {
                throw new \Exception("Gambar makanan tidak boleh kosong");
            }

            // Validasi format gambar
            $image_data = base64_decode($food_image, true);
            if ($image_data === false) {
                throw new \Exception("Data gambar tidak valid");
            }

            // Mendapatkan informasi format gambar
            $image_info = getimagesizefromstring($image_data);
            if ($image_info === false || !in_array($image_info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF])) {
                throw new \Exception("Format gambar tidak didukung");
            }

            // Membuat nama file gambar dengan format [nama_makanan - nomor_acak].[ekstensi]
            $random_number = rand(1000, 9999);
            $image_title = "$food_name - $random_number.jpg";
            $path = "assets/foods/$image_title";

            // Membuat data untuk dimasukkan ke dalam database
            $data = [
                'id_restaurant' => $id_restaurant,
                'food_name' => $food_name,
                'food_price' => $food_price,
                'food_quantity' => $food_quantity,
                'food_image' => $image_title,
                'food_desc' => $food_desc
            ];

            // Mendecode gambar
            $decoded = base64_decode($food_image);

            // Menyimpan gambar asli sementara untuk diproses
            $temp_path = "assets/foods/temp_$image_title";
            file_put_contents($temp_path, $decoded);

            // Mengurangi ukuran gambar menggunakan GD
            $new_width = 800; // Atur lebar baru
            $new_height = 600; // Atur tinggi baru

            // Membuat gambar dari file asli
            $source_image = imagecreatefromstring(file_get_contents($temp_path));
            if (!$source_image) {
                throw new \Exception("Gagal membuat gambar dari string");
            }

            $orig_width = imagesx($source_image);
            $orig_height = imagesy($source_image);

            // Menghitung proporsi baru
            $ratio = $orig_width / $orig_height;
            if ($new_width / $new_height > $ratio) {
                $new_width = $new_height * $ratio;
            } else {
                $new_height = $new_width / $ratio;
            }

            // Membuat gambar baru dengan ukuran baru
            $resized_image = imagecreatetruecolor($new_width, $new_height);
            if (!$resized_image) {
                throw new \Exception("Gagal membuat gambar yang diubah ukurannya");
            }

            if (!imagecopyresampled($resized_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height)) {
                throw new \Exception("Gagal mengubah ukuran gambar");
            }

            // Menyimpan gambar yang diubah ukurannya ke path yang diinginkan
            if (!imagejpeg($resized_image, $path, 90)) { // 90 adalah kualitas gambar
                throw new \Exception("Gagal menyimpan gambar yang diubah ukurannya");
            }

            // Membersihkan memori
            imagedestroy($source_image);
            imagedestroy($resized_image);

            // Menghapus gambar sementara
            unlink($temp_path);

            // Menyimpan data makanan ke dalam database
            $this->foodModel->insert($data);

            // Menyiapkan respons
            $response = [
                'status' => 200,
                'message' => 'Makanan Berhasil Ditambah.'
            ];
        } catch (\Exception $e) {
            // Menangani kesalahan dan menyiapkan respons kesalahan
            $response = [
                'status' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }

        // Mengembalikan respons
        return $this->respond($response);
    }

    public function edit_food()
    {
        // Mengambil data dari request
        $food_name = $this->request->getVar('food_name');
        $food_price = $this->request->getVar('food_price');
        $food_quantity = $this->request->getVar('food_quantity');
        $new_image = $this->request->getVar('new_image');
        $old_image = $this->request->getVar('old_image');
        $id_food = $this->request->getVar('id_food');
        $food_desc = $this->request->getVar('food_desc');

        // Jika ada gambar baru diunggah
        if ($new_image !== "no") {
            // Membuat nama file gambar baru dengan format [nama_makanan - nomor_acak].[ekstensi]
            $random_number = rand(1, 9999);
            $image_title = "$food_name - $random_number.jpg";
            $path = "assets/foods/$image_title";

            // Menghapus gambar lama
            unlink("assets/foods/" . $old_image);

            // Mendecode dan menyimpan gambar baru ke server
            $decoded = base64_decode($new_image);
            file_put_contents($path, $decoded);
        } else {
            // Jika tidak ada gambar baru, gunakan gambar lama
            $image_title = $old_image;
        }

        // Menyiapkan data yang akan diupdate
        $data = [
            'food_name' => $food_name,
            'food_price' => $food_price,
            'food_quantity' => $food_quantity,
            'food_image' => $image_title,
            'food_desc' => $food_desc,
        ];

        // Menyimpan data makanan yang telah diubah ke dalam database
        $this->foodModel->update($id_food, $data);

        // Menyiapkan respons
        $response = [
            'status' => 200,
            'message' => "Makanan Berhasil Diubah"
        ];

        // Mengembalikan respons
        return $this->respond($response);
    }

    public function delete_food()
    {
        // Mendapatkan id_food dari request
        $id_food = $this->request->getVar('id_food');

        // Membuat instance dari FoodModel
        $model = new FoodModel();

        // Mengambil data makanan berdasarkan id_food
        $food = $model->find($id_food);

        // Memeriksa apakah makanan ditemukan
        if ($food) {
            // Jika makanan ditemukan, hapus gambar terkait
            $path = 'assets/foods/' . $food['food_image'];
            unlink($path);

            // Hapus data makanan dari database
            $model->delete($id_food);

            // Menyiapkan respons
            $response = [
                'status' => 200,
                'message' => "Makanan Berhasil Dihapus"
            ];
        } else {
            // Jika makanan tidak ditemukan, kirim respons error
            $response = [
                'status' => 404,
                'message' => "Makanan Tidak Ditemukan"
            ];
        }

        // Mengembalikan respons
        return $this->respond($response);
    }
}
