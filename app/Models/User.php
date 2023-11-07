<?php

namespace App\Models;

use App\Http\Traits\Notify;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Notify,HasJsonRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'list_type_id' => 'array',
    ];


    protected $appends = ['mobile','profileName','photo'];

    protected $dates = ['sent_at'];


    public function getPhotoAttribute()
    {
        return getFile(config('location.user.path').$this->image);
    }

    public function getProfileNameAttribute()
    {
        return '@'. $this->username;
    }

    public function getMobileAttribute()
    {
        return $this->phone;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->mail($this, 'PASSWORD_RESET', $params = [
            'message' => '<a href="' . url('password/reset', $token) . '?email=' . $this->email . '" target="_blank">Click To Reset Password</a>'
        ]);
    }


    public function getDomains(){
        return $this->hasMany(Domain::class, 'user_id');
    }

    public function getRedirects(){
        return $this->hasMany(Redirect::class, 'user_id');
    }

    public function getIpLists(){
        return $this->belongsToMany(IPLIST::class, UserHasIpList::class, 'user_id', 'ip_list_id');
    }

    public function getListType(){
        return $this->belongsToJson(IPLIST::class, 'list_type_id','list_type_id');
    }

    public function getAllIpBlocks()
    {
        $ipList = $this->getIpLists->pluck('ip_address');

        $listType = $this->getListType->pluck('ip_address');

        $allIpBlocks = $ipList->concat($listType);

        return $allIpBlocks;
    }



}
