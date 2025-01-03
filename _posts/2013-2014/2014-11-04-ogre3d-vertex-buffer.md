---
layout: post
title: 'Ogre3D: работа с вертексным буфером'
type: post
categories:
- JFF
tags:
- графика
permalink: "/2014/11/04/ogre3d-%d1%80%d0%b0%d0%b1%d0%be%d1%82%d0%b0-%d1%81-%d0%b2%d0%b5%d1%80%d1%82%d0%b5%d0%ba%d1%81%d0%bd%d1%8b%d0%bc-%d0%b1%d1%83%d1%84%d0%b5%d1%80%d0%be%d0%bc/"
---
Ура :) Я научился таки работать с вертексным буфером в этом самам огре.

Как было просто в голом опенглы. Так просто, что даже вспомнить не хочется. :-D

Теперь же нам надо сделать что-то вроде этого

```cpp
void TutorialApplication::createScene(void)  
{

    /* Зачем дополнительно создавать submesh пока не понял */  
    Ogre::MeshPtr mesh = Ogre::MeshManager::getSingleton().createManual("CustomMesh", "General");  
    Ogre::SubMesh *subMesh = mesh->createSubMesh();

    /* Подготавливаем структуру для трех вершин (треугольник у нас) */  
    mesh->sharedVertexData = new Ogre::VertexData;  
    mesh->sharedVertexData->vertexCount = 3;

    /* Получаем ссылку на дескриптор буфера (описывает структуру) */  
    Ogre::VertexDeclaration *decl = mesh->sharedVertexData->vertexDeclaration;  
    size_t offset = 0;

    /* первый элемент буфера - это сама вершина (ее координаты) */  
    decl->addElement(0, offset, Ogre::VET_FLOAT3, Ogre::VES_POSITION);  
    offset += Ogre::VertexElement::getTypeSize(Ogre::VET_FLOAT3);

    /* вторая часть буфера - нормаль вершины */  
    decl->addElement(0, offset, Ogre::VET_FLOAT3, Ogre::VES_NORMAL);  
    offset += Ogre::VertexElement::getTypeSize(Ogre::VET_FLOAT3);

    /* Третья часть - это цвет вершины */  
    decl->addElement(0, offset, Ogre::VET_COLOUR, Ogre::VES_DIFFUSE);  
    offset += Ogre::VertexElement::getTypeSize(Ogre::VET_COLOUR);

    /* Генерируем вертексный буфер по описанию, которое выше */  
    Ogre::HardwareVertexBufferSharedPtr vertexBuffer = Ogre::HardwareBufferManager::getSingleton().  
        createVertexBuffer(offset, mesh->sharedVertexData->vertexCount, Ogre::HardwareBuffer::HBU_STATIC);

    // подготавливаем цвета  
    // можно писать цвета руками и использовать не Ogre::VET_COLOUR, а VET_FLOAT3|4 (4 - это если альфаканал нужен)  
    Ogre::RenderSystem* rs = Ogre::Root::getSingleton().getRenderSystem();  
    Ogre::uint32 red, green, blue;  
    rs->convertColourValue(Ogre::ColourValue(1,0,0,1), &red);  
    rs->convertColourValue(Ogre::ColourValue(0,1,0,1), &green);  
    rs->convertColourValue(Ogre::ColourValue(0,0,1,1), &blue);

    const float sqrt13 = 0.577350269f; /* sqrt(1/3) - это для нормалей */

    /* блокируем буфер на запись и берем указатель на него */  
    float *pVertex = static_cast<float *>(vertexBuffer->lock(Ogre::HardwareBuffer::HBL_DISCARD));

    // Заполняем буфер  
    *pVertex++ = 0.0f; *pVertex++ = 1.0f; *pVertex++ = 0.0f; // вершина  
    *pVertex++ = -sqrt13; *pVertex++ = sqrt13; *pVertex++ = -sqrt13; // нормаль  
    *(*(Ogre::uint32**)&pVertex)++ = red;   //цвета  
    *pVertex++ = -1.0f; *pVertex++ = -1.0f; *pVertex++ = 0.0f; // вершина  
    *pVertex++ = sqrt13; *pVertex++ = sqrt13; *pVertex++ = -sqrt13; // нормаль  
    *(*(Ogre::uint32**)&pVertex)++ = green; // цвета  
    *pVertex++ = 1.0f; *pVertex++ = -1.0f; *pVertex++ = 0.0f; // вершина  
    *pVertex++ = -sqrt13; *pVertex++ = -sqrt13; *pVertex++ = -sqrt13; // нормаль  
    *(*(Ogre::uint32**)&pVertex)++ = blue;  // цвета

    /* разблокируем */  
    vertexBuffer->unlock();

    /* Создаем буфер для индексов */  
    Ogre::HardwareIndexBufferSharedPtr indexBuffer = Ogre::HardwareBufferManager::getSingleton().  
        createIndexBuffer(Ogre::HardwareIndexBuffer::IT_16BIT, mesh->sharedVertexData->vertexCount, Ogre::HardwareBuffer::HBU_STATIC);

    /* Получаем блокировку на запись и пишем индексы в буфер */  
    uint16_t *indices = static_cast<uint16_t *>(indexBuffer->lock(Ogre::HardwareBuffer::HBL_NORMAL));

    /* Задаем нужный индексы вершин, которые будет треугольник представлять */  
    indices[0] = 0;  
    indices[1] = 1;  
    indices[2] = 2;

    /* записали - разблокировали */  
    indexBuffer->unlock();

    /* Теперь надо прицепить к нашей геометрии созданный буфер */  
    mesh->sharedVertexData->vertexBufferBinding->setBinding(0, vertexBuffer);  
    subMesh->useSharedVertices = true;  
    subMesh->indexData->indexBuffer = indexBuffer;  
    subMesh->indexData->indexCount = mesh->sharedVertexData->vertexCount;  
    subMesh->indexData->indexStart = 0;

    /* Если не объявить рамку, то огр не сможет правильно обсчитать сетку  
     * и она будет видна лишь в корневой ноде (если ее туда прицепить),  
     * а в дочерних - не будет.  
     * Для этого можно зачитать http://www.ogre3d.org/forums/viewtopic.php?f=2&t=60200  
     */  
    mesh->_setBounds(Ogre::AxisAlignedBox(-1, -1, -1, 1, 1, 1));

    /* нарисовали - грузим */  
    mesh->load();

    /*  
     * А теперь нужно задефайнить материал.  
     * Если этого не сделать, то новоиспеченный триангл будет выглядеть белым,  
     * а не многоцветным как задумано выше  
     */  
    Ogre::MaterialPtr material = Ogre::MaterialManager::getSingleton().create("Test/ColourTest", Ogre::ResourceGroupManager::DEFAULT_RESOURCE_GROUP_NAME);  
    material->getTechnique(0)->getPass(0)->setVertexColourTracking(Ogre::TVC_AMBIENT);

    /* Создаем ноду на базе того, что накодили выше. */  
    Ogre::Entity *entity = mSceneMgr->createEntity("CustomEntity", "CustomMesh", "General");  
    entity->setMaterialName("Test/ColourTest", "General");  
    Ogre::SceneNode *node = mSceneMgr->getRootSceneNode()->createChildSceneNode();  
    node->attachObject(entity);

    mCamera->lookAt(Ogre::Vector3(0, 0, 0));  
    mCamera->setPosition(Ogre::Vector3(0, 10, 50));  
}
```

 

![Ogre3D - использование вертексного буфера]({{ site.baseurl }}/assets/images/2014/11/d180d0b0d0b1d0bed187d0b5d0b5-d0bcd0b5d181d182d0be-1_109.png){:.img-fluid}

- https://grahamedgecombe.com/blog/custom-meshes-in-ogre3d
- http://www.ogre3d.org/forums/viewtopic.php?f=2&t=60200
- http://www.ogre3d.org/tikiwiki/tiki-index.php?page=Generating+A+Mesh

 

 

